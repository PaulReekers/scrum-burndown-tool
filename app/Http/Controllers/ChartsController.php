<?php
namespace App\Http\Controllers;

use Response;
use Exception;
use App\Chart;
use App\Board;
use Validator;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ChartsController extends Controller
{
    public function __construct()
    {
        // Set user authenthication with exceptions
        $this->middleware('auth',
            ['except' => [
                'index',
                'show',
                'store',
                'chartBurndown',
                'showSprint',
                'board',
                ]
            ]);
    }

    public function index()
    {
        $charts = Chart::groupBy('sprintname', 'slug', 'startDate', 'endDate')
            ->where('endDate' , '>' , Carbon::now())
            ->orderBy('startDate', 'desc')
            ->select('sprintname','slug', 'startDate', 'endDate')
            ->get();

        $oldCharts = Chart::groupBy('sprintname', 'slug', 'startDate', 'endDate')
            ->where('endDate' , '<' , Carbon::now())
            ->orderBy('startDate', 'desc')
            ->select('sprintname','slug', 'startDate', 'endDate')
            ->get();

        return view('charts.index', compact('charts', 'oldCharts'));
    }

    public function create($slug = null)
    {
        if ($slug)
        {
            $chart = Chart::where('slug', $slug)
                ->orderBy('sprintDay', 'desc')
                ->firstOrFail();
        }

        return view('charts.create', compact('chart'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'sprintname' => 'required|max:255',
            'storyPointsTotal' => 'required|numeric',
            'tasksTotal' => 'required|numeric',
            'tasksDone' => 'required|numeric',
            'storyPointsDone' => 'required|numeric',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'sprintDay' => 'required|date',
        ]);

        $chart = new Chart([
            'sprintname' => $request->get('sprintname'),
            'storyPointsTotal' => $request->get('storyPointsTotal'),
            'tasksTotal' => $request->get('tasksTotal'),
            'tasksDone' => $request->get('tasksDone'),
            'storyPointsDone' => $request->get('storyPointsDone'),
            'startDate' => $request->get('startDate'),
            'endDate' => $request->get('endDate'),
            'sprintDay' => $request->get('sprintDay'),
        ]);

        $chart->boardId = $request->get('boardId');
        $chart->slug = str_slug($request->get('sprintname'), '-');

        $chart->save();

        $request->session()->flash('success', 'Day ' .
            Carbon::parse($request->get('sprintDay'))->format('d-m-Y') .
            ' added.');

        return redirect('/charts/' . $chart->slug . '/edit');
    }

    public function edit($slug)
    {
        $charts = Chart::where('slug', $slug)
            ->orderBy('sprintDay', 'desc')
            ->get();

        return view('charts.edit', compact('charts'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'storyPointsTotal' => 'required|numeric',
            'tasksTotal' => 'required|numeric',
            'tasksDone' => 'required|numeric',
            'storyPointsDone' => 'required|numeric',
            'sprintDay' => 'required|date',
        ]);

        $chart = Chart::where('id', $id)->first();

        $chart->update($request->all());

        $request->session()->flash('success', 'Day ' .
            Carbon::parse($chart->sprintDay)->format('d-m-Y') .
            ' updated.');

        return redirect('/charts/' .$chart->slug . '/edit/');
    }

    public function destroy($id)
    {
        Chart::find($id)->delete();

        return redirect('/');
    }

    /**
     * Show burndown
     * @param  string $slug [description]
     * @return array
     */
    public function chartBurndown($slug)
    {
        if ($slug === null)
        {
            $request->session()->flash('failure', 'No burndown slug provided.');
        }

        $chart = Chart::where('slug', $slug)
            ->orderBy('sprintDay', 'asc')
            ->get();

        return $chart;
    }

    /**
     * With the board slug we show the latest active sprint
     * @param  string $slug unique board slug
     * @return array
     */
    public function board($slug)
    {
        $boardId = Board::where('slug', $slug)
            ->select('boardId')
            ->first();

        $chart = Chart::where('boardId', $boardId->boardId)
            ->orderBy('sprintDay', 'desc')
            ->first();

        /**
         * No active sprint found, create new sprint
         */
        if ($chart === null)
        {
            return redirect('/chart/' . $boardId['boardId'] . '/update');
        }

        $chartInfo = $this->chartExtraInfo($chart->boardId, $chart->sprintname);
        $chartInfo->currentStoryPoints = $chart->storyPointsTotal;
        $chartInfo->currentStoryPointsDone = $chart->storyPointsDone;

        return view('charts.show', compact('chart', 'chartInfo'));
    }

    /**
     * Check and show data from requested sprint-slug
     * @param  string $slug Sprint unique slug
     * @return array
     */
    public function showSprint($slug)
    {
        $chart = Chart::where('slug', $slug)
            ->orderBy('sprintDay', 'desc')
            ->first();

        if ($chart)
        {
            $chartInfo = $this->chartExtraInfo($chart->boardId, $chart->sprintname);
            $chartInfo->currentStoryPoints = $chart->storyPointsTotal;
            $chartInfo->currentStoryPointsDone = $chart->storyPointsDone;

            return view('charts.show', compact('chart', 'chartInfo'));
        }
            else
        {
            // Slug is invalid
            return abort(404);
        }
    }

    protected function chartExtraInfo($boardId, $sprintName)
    {
        $firstDay = Chart::where('boardId', $boardId)
            ->where('sprintname', $sprintName)
            ->orderBy('sprintDay', 'asc')
            ->first();

        $chartInfo = $firstDay;

        return $chartInfo;
    }
}
