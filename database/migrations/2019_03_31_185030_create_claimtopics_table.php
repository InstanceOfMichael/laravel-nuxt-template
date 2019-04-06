<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimtopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claimtopics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('topic_id')->index();
            $table->unsignedBigInteger('claim_id')->index();
            $table->unsignedBigInteger('op_id')->index();
            $table->timestamps();

            $table->foreign('op_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('topic_id')
                ->references('id')
                ->on('topics')
                ->onDelete('cascade');
            $table->foreign('claim_id')
                ->references('id')
                ->on('claims')
                ->onDelete('cascade');

            $table->unique(['topic_id', 'claim_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claimtopics');
    }
}
