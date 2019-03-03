<?php

namespace Tests\Unit;

use App\Claim;
use App\Comment;
use App\Question;
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
        $claim = $user->claims()->create(factory(Claim::class)->raw());
        $question = $user->questions()->create(factory(Question::class)->raw());
        $answer = factory(Answer::class)->create([
            'op_id' => $user->id,
            'claim_id' => $claim->id,
            'question_id' => $question->id,
        ]);
        $comment = $claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $user->id,
        ]));

        $this->assertSerializedDatetimesAreInts($user);
        $this->assertSerializedDatetimesAreInts($claim);
        $this->assertSerializedDatetimesAreInts($question);
        $this->assertSerializedDatetimesAreInts($answer);
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
