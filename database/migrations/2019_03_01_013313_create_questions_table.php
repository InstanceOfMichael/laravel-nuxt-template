<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('op_id')->index();
            $table->unsignedTinyInteger('sides_type')->index();
            $table->string('title');
            $table->text('text');

            $table->timestamps();

            $table->unsignedSmallInteger('comments_count')->index()->default(0);
            $table->unsignedSmallInteger('answers_count')->index()->default(0);
            $table->unsignedSmallInteger('sides_count')->index()->default(0);
            $table->unsignedSmallInteger('topics_count')->index()->default(0);

            $table->foreign('op_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
