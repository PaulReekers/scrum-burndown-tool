<?php

namespace App\Http\Controllers;

use App\Chart;
use App\Board;
use Carbon\Carbon;
use App\Jira\Burndown;

class JiraController extends Controller
{
    public function store($slug)
    {
        $board = Board::where('slug', $slug)->first();

        $burndown = new Burndown($board->boardId);
        $sprintInfo = $burndown->getSprintNumber();
        $sprintProgress = $burndown->getSprintProgress();
        $storyPointsDone = $sprintProgress["done"];
        $tasksDone = $burndown->getFilterTotalCount($board->tasksDoneFilter);
        $startDate = Carbon::parse($sprintInfo['values'][0]['startDate'])->toDateString();
        $endDate = Carbon::parse($sprintInfo['values'][0]['endDate'])->toDateString();

        $dayExists = Chart::where('boardId', '=', $board->boardId)
            ->where('sprintDay', '=', Carbon::now()->toDateString())
            ->where('sprintname', '=', $sprintInfo['values'][0]['name'])
            ->exists();

        if ($dayExists) {
            throw new Exception('You already saved this day');
        }

        if ($startDate == Carbon::now()->toDateString()) {
            $tasksDone = 0;
            $storyPointsDone = 0;
        }

        $chart = new Chart([
            'sprintname' => $sprintInfo['values'][0]['name'],
            'storyPointsTotal' => $sprintProgress['total'],
            'tasksTotal' => $burndown->getFilterTotalCount($board->totalTaskFilter),
            'tasksDone' => $tasksDone,
            'storyPointsDone' => $storyPointsDone,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'sprintDay' => Carbon::now()->toDateString(),
        ]);

        $chart->boardId = $board->boardId;
        $chart->slug = str_slug($sprintInfo['values'][0]['name'], '-');

        $chart->save();

        $this->updateEndDate($sprintInfo);

        return 'Saved new day for sprint: ' . $sprintInfo['values'][0]['name'];
    }

    public function update($slug)
    {
        $board = Board::where('slug', $slug)->first();

        $burndown = new Burndown($board->boardId);

        $sprintInfo = $burndown->getSprintNumber();
        $sprintProgress = $burndown->getSprintProgress();

        $chart = Chart::where('boardId', '=', $board->boardId)
            ->where('sprintDay', '=', Carbon::now()->toDateString())
            ->where('sprintname', '=', $sprintInfo['values'][0]['name'])
            ->first();

        if (!$chart) {
            return $this->store($board->slug);
        }

        $request = new Chart([
            'storyPointsTotal' => $sprintProgress['total'],
            'tasksTotal' => $burndown->getFilterTotalCount($board->totalTaskFilter),
            'tasksDone' => $burndown->getFilterTotalCount($board->tasksDoneFilter),
            'storyPointsDone' => $sprintProgress['done'],
        ]);

        $chart->update($request->toArray());

        $this->updateEndDate($sprintInfo);

        return 'Sprint updated';
    }

    public function updatechart($boardId)
    {
        $board = Board::where('boardId', $boardId)
            ->orderBy('id', 'desc')
            ->first();

        // No active board found
        if (!$board) {
            return redirect('/');
        }

        $this->update($board->slug);

        return redirect('/' . $board->slug);
    }

    /**
     * Same Start and End date for current sprint entries
     * If for anyreason the date of the sprint is changed, correct it for the
     * already stored days.
     */
    protected function updateEndDate($sprintInfo)
    {
        $startDate = Carbon::parse($sprintInfo['values'][0]['startDate'])->toDateString();
        $endDate = Carbon::parse($sprintInfo['values'][0]['endDate'])->toDateString();

        Chart::where('sprintname', '=', $sprintInfo['values'][0]['name'])
            ->update([
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
    }
}
