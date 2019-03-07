<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllowedquestionsidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowedquestionsides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('side_id')->index()->nullable();
            $table->unsignedBigInteger('question_id')->index();
            $table->unsignedBigInteger('op_id')->index();
            $table->timestamps();

            $table->foreign('op_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('side_id')
                ->references('id')
                ->on('sides')
                ->onDelete('cascade');
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->unique(['side_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowedquestionsides');
    }
}
