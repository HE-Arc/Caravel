<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelatedTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_tasks', function (Blueprint $table) {
            $table->bigInteger('related_id')->unsigned();
            $table->index('related_id');
            
            $table->bigInteger('task_id')->unsigned();
            $table->index('task_id');            

            $table->timestamps();
        });

        Schema::table('related_tasks', function (Blueprint $table) {
            $table->foreign('related_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('related_tasks');
    }
}
