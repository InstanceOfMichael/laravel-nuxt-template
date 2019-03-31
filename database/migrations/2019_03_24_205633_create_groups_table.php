<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('text');
            $table->unsignedBigInteger('op_id')->index();
            $table->timestamps();

            $table->foreign('op_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE groups ADD CONSTRAINT clength_name CHECK (char_length(name) > 2);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
