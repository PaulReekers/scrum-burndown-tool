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
                'burndown',
                'showslug',
                ]
            ]);
    }

    public function burndown($slug)
    {
        if ($slug === null) {
            $request->session()->flash('failure', 'No burndown slug provided.');
        }

        $chart = Chart::where('slug', $slug)
            ->orderBy('sprintDay', 'asc')
            ->get();

        return $chart;
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
        if ($slug) {
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

    public function show($slug)
    {
        $boardId = Board::where('slug', $slug)
            ->select('boardId')
            ->first();

        $chart = Chart::where('boardId', $boardId['boardId'])
            ->orderBy('id', 'desc')
            ->first();

        if ($chart === null) {
            return redirect ('/chart/' . $boardId['boardId'] . '/update');
        }

        return view('charts.show', compact('chart'));
    }

    public function showslug($slug)
    {
        $chart = Chart::where('slug', $slug)
            ->orderBy('sprintDay', 'desc')
            ->first();

        if ($chart)
        {
            return view('charts.show', compact('chart'));
        } else {
            // Slug is invalid
            return abort(404);
        }
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
}
