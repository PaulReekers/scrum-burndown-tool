<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('boardId');
            $table->string('sprintname')->index();
            $table->string('slug')->index();
            $table->double('storyPointsTotal', 5, 1);
            $table->double('tasksTotal', 5, 1);
            $table->double('tasksDone', 5, 1);
            $table->double('storyPointsDone', 5, 1);
            $table->date('startDate');
            $table->date('endDate');
            $table->date('sprintDay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charts');
    }
}
