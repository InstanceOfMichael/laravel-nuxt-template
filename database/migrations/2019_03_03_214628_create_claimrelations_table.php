<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimrelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claimrelations', function (Blueprint $table) {
            $table->bigIncrements('id');
            // parent claim
            $table->unsignedBigInteger('pc_id')->index();
            // reply claim
            $table->unsignedBigInteger('rc_id')->index();
            // original poster
            $table->unsignedBigInteger('op_id')->index();

            $table->unsignedTinyInteger('type')->index();

            $table->timestamps();

            $table->foreign('op_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('rc_id')
                ->references('id')
                ->on('claims')
                ->onDelete('cascade');
            $table->foreign('pc_id')
                ->references('id')
                ->on('claims')
                ->onDelete('cascade');

            $table->unique(['pc_id', 'rc_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claimrelations');
    }
}
