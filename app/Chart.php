<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'sprintname',
        'storyPointsTotal',
        'tasksTotal',
        'tasksDone',
        'storyPointsDone',
        'startDate',
        'endDate',
        'sprintDay',
    ];
}
