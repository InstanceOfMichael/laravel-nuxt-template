<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // An answer is a pivot table claiming that this claim is an answer to this question
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('claim_id')->index();
            $table->unsignedBigInteger('question_id')->index();
            $table->unsignedBigInteger('side_id')->index()->nullable();
            $table->unsignedBigInteger('op_id')->index();
            $table->timestamps();

            $table->foreign('op_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('claim_id')
                ->references('id')
                ->on('claims')
                ->onDelete('cascade');
            $table->foreign('side_id')
                ->references('id')
                ->on('sides')
                ->onDelete('set null');
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->unique(['claim_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
