<?php

namespace App\Console\Commands;

use App\Chart;
use App\Board;
use Carbon\Carbon;
use App\Jira\Burndown;
use Illuminate\Console\Command;

class UpdateAllBoards extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'jira:savenewday';
    protected $description = 'Stores currend new day for each Jira board';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $boards = Board::all();

        foreach($boards as $key => $board) {
            $burndown = new Burndown($board->boardId);
            $sprintInfo = $burndown->getSprintNumber();
            $sprintProgress = $burndown->getSprintProgress();

            $dayExists = Chart::where('boardId', '=', $board->boardId)
                ->where('sprintDay', '=', Carbon::now()->format('Y-m-d'))
                ->where('sprintname', '=', $sprintInfo['values'][0]['name'])
                ->exists();

            if ($dayExists)
            {
                $this->info('Sprint data ' .
                    $sprintInfo['values'][0]['name'] .
                    ' already saved for ' .
                    Carbon::now()->format('Y-m-d'));

            } else {
                $chart = new Chart([
                    'sprintname' => $sprintInfo["values"][0]["name"],
                    'storyPointsTotal' => $sprintProgress["total"],
                    'tasksTotal' => $burndown->getFilterTotalCount($board->totalTaskFilter),
                    'tasksDone' => $burndown->getFilterTotalCount($board->tasksDoneFilter),
                    'storyPointsDone' => $sprintProgress["done"],
                    'startDate' => Carbon::parse($sprintInfo["values"][0]["startDate"])
                        ->format('Y-m-d'),
                    'endDate' => Carbon::parse($sprintInfo["values"][0]["endDate"])
                        ->format('Y-m-d'),
                    'sprintDay' => Carbon::now()
                        ->format('Y-m-d'),
                ]);

                $chart->boardId = $board->boardId;
                $chart->slug = str_slug($sprintInfo["values"][0]["name"], '-');

                $chart->save();

                $this->info('Saving new ' .
                    Carbon::now()->format('Y-m-d') .
                    ' data for sprint: ' .
                    $sprintInfo["values"][0]["name"]);
            }
        }

        // Done, all boards have been checked and have current day data stored.
        $this->info( 'Saved ' . Carbon::now()->format('Y-m-d') . ' data for all Jira boards');
    }
}
