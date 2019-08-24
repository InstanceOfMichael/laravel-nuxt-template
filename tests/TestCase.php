<?php

namespace Tests;

use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    protected $faker;

    public function setUp() {
        parent::setUp();
        app(\Illuminate\Contracts\Hashing\Hasher::class)->setRounds(4);
        DB::statement('TRUNCATE users, password_resets, oauth_providers, comments CASCADE;');
        $this->faker = app(\Faker\Generator::class);
        $this->faker->seed(get_class($this));
    }
}
