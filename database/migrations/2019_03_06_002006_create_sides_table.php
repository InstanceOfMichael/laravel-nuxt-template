<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 128);
            $table->text('text')->default('');
            $table->unsignedBigInteger('op_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('op_id')
                ->references('id')
                ->on('users');
        });

        DB::statement('CREATE UNIQUE INDEX uilc_name ON sides (lower(name));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sides');
    }
}
