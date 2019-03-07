<?php

use Illuminate\Database\Seeder;
use App\Side;
use App\User;

class SidesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::query()->where('handle', 'app')->firstOrFail();

        Side::firstOrCreate([
            'name' => 'True',
        ]);
        Side::firstOrCreate([
            'name' => 'False',
        ]);
        Side::firstOrCreate([
            'name' => 'Yes',
        ]);
        Side::firstOrCreate([
            'name' => 'No',
        ]);
        Side::firstOrCreate([
            'name' => 'Misleading',
        ]);
    }
}
