<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HasFinish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('has_finish', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->morphs('element');
            $table->primary(['element_id', 'element_type', 'user_id']);
            $table->integer('isSubscribed')->default(0);
            $table->timestamps();
        });

        Schema::table('has_finish', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
