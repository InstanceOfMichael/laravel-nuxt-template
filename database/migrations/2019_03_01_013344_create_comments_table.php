<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            // parent comment id
            $table->unsignedBigInteger('pc_id')->index()->default(0);
            // original poster id
            $table->unsignedBigInteger('op_id')->index();
            $table->unsignedBigInteger('context_id');
            $table->unsignedTinyInteger('context_type');

            // if it is a reply
            $table->unsignedSmallInteger('depth')->default(0);
            $table->unsignedSmallInteger('reply_count')->default(0);

            $table->timestamps();
            $table->text('text');

            $table->index(['context_type', 'context_id']);

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
        Schema::dropIfExists('comments');
    }
}
