<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'handle' => 'app',
            'name' => 'app',
            'email' => 'app@example.com',
        ]);
        User::firstOrCreate([
            'handle' => 'iom',
            'name' => 'iom',
            'email' => 'iom@codebro.org',
            'password' => bcrypt('Buffalo4ever!'),
        ]);
    }
}
