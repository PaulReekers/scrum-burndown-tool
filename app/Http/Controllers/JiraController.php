<?php

namespace App\Http\Controllers;

use Exception;
use App\Chart;
use App\Board;
use Carbon\Carbon;
use App\Jira\Burndown;
use Illuminate\Http\Request;

class JiraController extends Controller
{
    public function store($slug)
    {
        $board = Board::where('slug', $slug)->first();

        $burndown = new Burndown($board->boardId);
        $sprintInfo = $burndown->getSprintNumber();
        $sprintProgress = $burndown->getSprintProgress();

        $dayExists = Chart::where('boardId', '=', $board->boardId)
            ->where('sprintDay', '=', Carbon::now()->format('Y-m-d'))
            ->where('sprintname', '=', $sprintInfo['values'][0]['name'])
            ->exists();

        if ($dayExists)
        {
            throw new Exception('You already saved this day');
        }

        $chart = new Chart([
            'sprintname' => $sprintInfo['values'][0]['name'],
            'storyPointsTotal' => $sprintProgress['total'],
            'tasksTotal' => $burndown->getFilterTotalCount($board->totalTaskFilter),
            'tasksDone' => $burndown->getFilterTotalCount($board->tasksDoneFilter),
            'storyPointsDone' => $sprintProgress['done'],
            'startDate' => Carbon::parse($sprintInfo['values'][0]['startDate'])
                ->format('Y-m-d'),
            'endDate' => Carbon::parse($sprintInfo['values'][0]['endDate'])
                ->format('Y-m-d'),
            'sprintDay' => Carbon::now()
                ->format('Y-m-d'),
        ]);

        $chart->boardId = $board->boardId;
        $chart->slug = str_slug($sprintInfo['values'][0]['name'], '-');

        $chart->save();

        return 'Saved new day for sprint: ' . $sprintInfo['values'][0]['name'];
    }

    public function update($slug)
    {
        $board = Board::where('slug', $slug)->first();

        $burndown = new Burndown($board->boardId);

        $sprintInfo = $burndown->getSprintNumber();
        $sprintProgress = $burndown->getSprintProgress();

        $chart = Chart::where('boardId', '=', $board->boardId)
            ->where('sprintDay', '=', Carbon::now()->format('Y-m-d'))
            ->where('sprintname', '=', $sprintInfo['values'][0]['name'])
            ->first();

        if (!$chart)
        {
            return $this->store($board->slug);
        }

        $request = new Chart([
            'storyPointsTotal' => $sprintProgress['total'],
            'tasksTotal' => $burndown->getFilterTotalCount($board->totalTaskFilter),
            'tasksDone' => $burndown->getFilterTotalCount($board->tasksDoneFilter),
            'storyPointsDone' => $sprintProgress['done'],
        ]);

        $chart->update($request->toArray());

        return 'Sprint updated';
    }

    public function updatechart($boardId)
    {
        $board = Board::where('boardId', $boardId)
            ->orderBy('id', 'desc')
            ->first();

        $this->update($board->slug);

        return redirect('/' . $board->slug);
    }
}
