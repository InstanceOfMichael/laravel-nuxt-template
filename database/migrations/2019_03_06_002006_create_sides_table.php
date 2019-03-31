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
            $table->timestamps();
        });

        DB::statement('CREATE UNIQUE INDEX uilc_sidename ON sides (lower(name));');
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
