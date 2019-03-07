<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('handle', 32); // we index later
            $table->string('name');
            $table->string('email'); // we index later
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE users ADD CONSTRAINT clength_email CHECK (char_length(email) > 5);');
        DB::statement('ALTER TABLE users ADD CONSTRAINT clc_email CHECK (email = lower(email));');
        DB::statement('CREATE UNIQUE INDEX uilc_email ON users (lower(email));');

        // DB::statement('ALTER TABLE users ADD CONSTRAINT clc_handle CHECK (handle = unaccent(lower(handle)));');
        // DB::statement('CREATE UNIQUE INDEX uilc_handle ON users (unaccent(lower(handle)));');
        DB::statement('ALTER TABLE users ADD CONSTRAINT clength_handle CHECK (char_length(handle) > 2);');
        DB::statement('ALTER TABLE users ADD CONSTRAINT clength_name CHECK (char_length(name) > 2);');

        // user handle must match a regex of only azAZ09-_ after all else
        DB::statement('ALTER TABLE users ADD CONSTRAINT cre_handle CHECK (handle ~ \'^[a-zA-Z0-9_\-]+$\');');
        DB::statement('CREATE UNIQUE INDEX uilc_handle ON users (lower(handle));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
