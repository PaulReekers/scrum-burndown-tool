<?php

namespace App\Console\Commands;

use App\Chart;
use App\Board;
use Carbon\Carbon;
use App\Jira\Burndown;
use Illuminate\Console\Command;

class UpdateAllBoards extends Command
{
    protected $signature = 'jira:savenewday';
    protected $description = 'Stores currend day for each Jira board';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $boards = Board::all();

        foreach ($boards as $key => $board) {
            $burndown = new Burndown($board->boardId);
            $sprintInfo = $burndown->getSprintNumber();
            $sprintProgress = $burndown->getSprintProgress();
            $storyPointsDone = $sprintProgress["done"];
            $tasksDone = $burndown->getFilterTotalCount($board->tasksDoneFilter);
            $sprintName = $sprintInfo['values'][0]['name'];
            $startDate = Carbon::parse($sprintInfo['values'][0]['startDate'])->toDateString();
            $endDate = Carbon::parse($sprintInfo['values'][0]['endDate'])->toDateString();

            $dayExists = Chart::where('boardId', '=', $board->boardId)
                ->where('sprintDay', '=', Carbon::now()->toDateString())
                ->where('sprintname', '=', $sprintName)
                ->exists();

            if ($startDate == Carbon::now()->toDateString()) {
                $tasksDone = 0;
                $storyPointsDone = 0;
            }

            if ($dayExists) {
                $this->info('Sprint data ' .
                    $sprintName .
                    ' already saved for ' .
                    Carbon::now()->toDateString());
            } else {
                $chart = new Chart([
                    'sprintname' => $sprintName,
                    'storyPointsTotal' => $sprintProgress["total"],
                    'tasksTotal' => $burndown->getFilterTotalCount($board->totalTaskFilter),
                    'tasksDone' => $tasksDone,
                    'storyPointsDone' => $storyPointsDone,
                    'startDate' => Carbon::parse($startDate)->toDateString(),
                    'endDate' => Carbon::parse($endDate)->toDateString(),
                    'sprintDay' => Carbon::now()->toDateString(),
                ]);

                $chart->boardId = $board->boardId;
                $chart->slug = str_slug($sprintName, '-');

                $chart->save();

                // Update all start and enddate with last get
                Chart::where('sprintname', '=', $sprintName)
                    ->update([
                        'startDate' => $startDate,
                        'endDate' => $endDate,
                    ]);

                $this->info('Saving new ' .
                    Carbon::now()->toDateString() .
                    ' data for sprint: ' .
                    $sprintName);
            }
        }

        // Done, all boards are checked and have today's data stored.
        $this->info('Saved ' . Carbon::now()->toDateString() . ' data for all Jira boards');
    }
}
