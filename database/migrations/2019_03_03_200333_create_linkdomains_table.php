<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkdomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linkdomains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain', 255)->unique();
            $table->string('name', 128)->default('');
            $table->text('text')->default('');
            $table->json('meta')->default('{}');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('linkdomains');
    }
}
