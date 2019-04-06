<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestiontopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questiontopics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('topic_id')->index();
            $table->unsignedBigInteger('question_id')->index();
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
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->unique(['topic_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questiontopics');
    }
}
