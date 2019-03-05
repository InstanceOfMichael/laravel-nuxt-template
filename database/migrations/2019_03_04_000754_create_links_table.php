<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('op_id')->index();
            // linkdomain_id
            $table->unsignedBigInteger('ld_id')->index();
            $table->string('title');
            $table->string('url', 2083);
            $table->json('meta')->default('{}');
            $table->timestamps();

            $table->foreign('op_id')
                ->references('id')
                ->on('users')
                // ->onDelete('cascade')
                ;

            $table->foreign('ld_id')
                ->references('id')
                ->on('linkdomains')
                // ->onDelete('cascade')
                ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
