<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SettingUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_user', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('action_type_id');
            $table->primary(['action_type_id', 'user_id']);
            $table->boolean('isMailEnabled')->nullable()->default(false);
            $table->boolean('isInternalEnabled')->nullable()->default(false);
        });

        Schema::table('setting_user', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('action_type_id')->references('id')->on('action_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setting_user');
    }
}
