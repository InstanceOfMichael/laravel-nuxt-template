<?php

namespace Tests\Unit;

use DB;
use App\User;
use Tests\TestCase;

class UserEmailLowerCaseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testModelAutoconverts()
    {
        $mixedEmail = 'MixedEmail@example.com';
        $user = new User([
            'email' => $mixedEmail,
        ]);
        $this->assertEquals(strtolower($mixedEmail), $user->email);

        $mixedEmail = 'MixedEmail@example.com';
        $user = new User([
            'email' => strtoupper($mixedEmail),
        ]);
        $this->assertEquals(strtolower($mixedEmail), $user->email);

        $mixedEmail = 'MixedEmail@example.com';
        $user = new User([
            'email' => strtolower($mixedEmail),
        ]);
        $this->assertEquals(strtolower($mixedEmail), $user->email);
    }

    /**
     * @expectedException     PDOException
     * @expectedExceptionCode 23514
     * @expectedExceptionMessageRegExp /^SQLSTATE\[23514\]: Check violation: 7 ERROR:  new row for relation "users" violates check constraint "clc_email"/
     */
    public function testDatabaseRejects() {
        $mixedEmail = 'MixedEmail@example.com';
        DB::table('users')->insert([
            'name' => '',
            'email' => strtoupper($mixedEmail),
        ]);
    }
}
