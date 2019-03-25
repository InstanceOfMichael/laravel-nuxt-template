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
        ], Comment::query()->pluck('context_type')->all());

        $this->assertNotNull($comment1->context());
        $this->assertNotNull($comment1->context);
        $this->assertEquals(
            'select * from "questions" where "questions"."id" = ?',
            $comment1->context()->toSql());
        $this->assertEquals(
            get_class($comment1->context),
            get_class($question));
        $this->assertEquals(
            $comment1->context->getAttributes(),
            $question->getAttributes());

        $this->assertNotNull($comment2->context());
        $this->assertNotNull($comment2->context);
        $this->assertEquals(
            'select * from "claims" where "claims"."id" = ?',
            $comment2->context()->toSql());
        $this->assertEquals(
            get_class($comment2->context),
            get_class($claim));
        $this->assertEquals(
            $comment2->context->getAttributes(),
            $claim->getAttributes());
    }
}
