<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claimsides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('side_id')->index();
            $table->unsignedBigInteger('claim_id')->index();
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
            $table->foreign('claim_id')
                ->references('id')
                ->on('claims')
                ->onDelete('cascade');

            $table->unique(['side_id', 'claim_id']);
        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claimsides');
    }
}
