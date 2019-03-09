<?php

namespace Tests\Unit;

use App\Comment;
use App\Claim;
use App\Question;
use App\User;
use Tests\TestCase;

class CommentTopicMorphMapTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @group comment
     *
     * @return void
     */
    public function testBasicTest()
    {
        $users = factory(User::class, 3)->create();
        $question = factory(Question::class)->create([
            'op_id' => $users[0]->id,
        ]);
        $comment1 = $question->comments()->create(factory(Comment::class)->raw([
            'op_id' => $users[1]->id,
        ]));
        $claim = factory(Claim::class)->create([
            'op_id' => $users[0]->id,
        ]);
        $comment2 = $claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $users[2]->id,
        ]));
        $this->assertEquals(2, Comment::count());
        $this->assertEquals([
            $question->getMorphClass(),
            $claim->getMorphClass(),
        ], Comment::query()->pluck('topic_type')->all());

        $this->assertNotNull($comment1->topic());
        $this->assertNotNull($comment1->topic);
        $this->assertEquals(
            'select * from "questions" where "questions"."id" = ?',
            $comment1->topic()->toSql());
        $this->assertEquals(
            get_class($comment1->topic),
            get_class($question));
        $this->assertEquals(
            $comment1->topic->getAttributes(),
            $question->getAttributes());

        $this->assertNotNull($comment2->topic());
        $this->assertNotNull($comment2->topic);
        $this->assertEquals(
            'select * from "claims" where "claims"."id" = ?',
            $comment2->topic()->toSql());
        $this->assertEquals(
            get_class($comment2->topic),
            get_class($claim));
        $this->assertEquals(
            $comment2->topic->getAttributes(),
            $claim->getAttributes());
    }
}
