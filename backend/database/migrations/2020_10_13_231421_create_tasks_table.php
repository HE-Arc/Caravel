<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {           
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('start_at');
            $table->date('due_at');
            $table->timestamps();
            $table->boolean('isPrivate')->nullable()->default(false);
            $table->bigInteger('task_group_id')->unsigned();
            $table->foreignId('user_id');
            $table->foreignId('tasktype_id');
            $table->foreignId('subject_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tasktype_id')->references('id')->on('tasktypes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
