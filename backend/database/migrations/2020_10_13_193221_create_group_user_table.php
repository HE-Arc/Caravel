<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned();
            $table->index('user_id');
            
            $table->bigInteger('group_id')->unsigned();
            $table->index('group_id');
            
            $table->primary(['group_id', 'user_id']);

            $table->timestamps();
            $table->integer('isApprouved')->default(0); // 0 -> pending, 1 -> refused, 2 accepted
        });

        Schema::table('group_user', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_user');
    }
}
