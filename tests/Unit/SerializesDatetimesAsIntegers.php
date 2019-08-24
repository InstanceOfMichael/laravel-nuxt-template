<?php

namespace Tests\Unit;

use App\Comment;
use App\User;
use Tests\TestCase;

class SerializesDatetimesAsIntegers extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $user = factory(User::class)->create();
        $comment = $user->comments()
            ->create(factory(Comment::class)->raw());

        $this->assertSerializedDatetimesAreInts($user);
        $this->assertSerializedDatetimesAreInts($comments);
    }

    protected function assertIsInt($value) {
        $this->assertEquals('integer', gettype($value));
        $this->assertTrue(is_scalar($value));
        $this->assertTrue(is_int($value));
    }

    protected function assertSerializedDatetimesAreInts($model) {
        $arr = $model->toArray();

        if (array_key_exists('created_at', $arr)) {
            $this->assertIsInt($arr['created_at']);
        }
        if (array_key_exists('updated_at', $arr)) {
            $this->assertIsInt($arr['updated_at']);
        }
        if (array_key_exists('deleted_at', $arr)) {
            $this->assertIsInt($arr['deleted_at']);
        }
    }
}
