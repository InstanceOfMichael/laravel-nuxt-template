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

        DB::statement('TRUNCATE users, password_resets, oauth_providers, questions, comments, answers, claims, claimrelations, links, linkdomains CASCADE;');
        $this->faker = app(\Faker\Generator::class);
        $this->faker->seed(get_class($this));
    }
}
