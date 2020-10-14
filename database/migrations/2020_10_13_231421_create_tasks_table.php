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
            $table->string('title', 150);
            $table->date('due_at');
            $table->boolean('isPrivate')->nullable()->default(false);
            $table->text('description')->nullable();
            $table->integer('number')->unsigned()->default(0);
            $table->timestamps();

            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->index('user_id');

            $table->bigInteger('tasktype_id')->unsigned();
            $table->index('tasktype_id');

            $table->bigInteger('subject_id')->unsigned();
            $table->index('subject_id');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
