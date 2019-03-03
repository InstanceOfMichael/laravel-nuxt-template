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
        $user = factory(User::class)->make([
            'email' => $mixedEmail,
        ]);
        $this->assertEquals(strtolower($mixedEmail), $user->email);

        $mixedEmail = 'MixedEmail@example.com';
        $user = factory(User::class)->make([
            'email' => strtoupper($mixedEmail),
        ]);
        $this->assertEquals(strtolower($mixedEmail), $user->email);

        $mixedEmail = 'MixedEmail@example.com';
        $user = factory(User::class)->make([
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

        $attr = factory(User::class)->make([
            'email' => strtoupper($mixedEmail),
        ])->getAttributes();

        $attr['email'] = strtoupper($mixedEmail);

        DB::table('users')->insert($attr);
    }
}
