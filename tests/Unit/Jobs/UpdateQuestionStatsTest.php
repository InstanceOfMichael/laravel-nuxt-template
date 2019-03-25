<?php

namespace Tests\Unit\Jobs;

use App\Allowedquestionside;
use App\Answer;
use App\Claim;
use App\Comment;
use App\Jobs\UpdateQuestionStats;
use App\Question;
use App\Side;
use App\User;
use Tests\TestCase;

class UpdateQuestionStatsTest extends TestCase
{

    private function stats (Question $question) {
        return [
            'answers_count' => $question->answers_count,
            'comments_count' => $question->comments_count,
            'sides_count' => $question->sides_count,
        ];
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testQuestionWithNoRelations()
    {
        $user = factory(User::class)->create();
        $question = factory(Question::class)->create([
            'op_id' => $user->id,
        ]);

        dispatch_now(new UpdateQuestionStats($question));

        $this->assertEquals([
            'answers_count' => 0,
            'comments_count' => 0,
            'sides_count' => 0,
        ], $this->stats($question->fresh()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testQuestionWithAllRelationsSideTypeAllow()
    {
        $user = factory(User::class)->create();
        $question = factory(Question::class)->create([
            'sides_type' => Side::TYPE_ALLOW,
            'op_id' => $user->id,
        ]);

        $question->comments()->create(factory(Comment::class)->raw([
            'op_id' => $user->id,
        ]));

        $sides = factory(Side::class, 3)->create([
            'op_id' => $user->id,
        ]);
        foreach($sides as $side) {
            $allowedquestionsides = factory(Allowedquestionside::class)->create([
                'op_id' => $user->id,
                'question_id' => $question->id,
                'side_id' => $side->id,
            ]);
            $answers = factory(Answer::class, 2)->create([
                'op_id' => $user->id,
                'question_id' => $question->id,
                'side_id' => $side->id,
                'claim_id' => function () use ($user) {
                    return factory(Claim::class)->create([
                        'op_id' => $user->id,
                    ])->id;
                },
            ]);
        }

        dispatch_now(new UpdateQuestionStats($question));

        $this->assertEquals([
            'answers_count' => 6,
            'comments_count' => 1,
            'sides_count' => 3,
        ], $this->stats($question->fresh()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testQuestionWithAllRelationsSideTypeAny()
    {
        $user = factory(User::class)->create();
        $question = factory(Question::class)->create([
            'sides_type' => Side::TYPE_ANY,
            'op_id' => $user->id,
        ]);

        $question->comments()->create(factory(Comment::class)->raw([
            'op_id' => $user->id,
        ]));

        $sides = factory(Side::class, 3)->create([
            'op_id' => $user->id,
        ]);
        foreach($sides as $side) {
            $allowedquestionsides = factory(Allowedquestionside::class)->create([
                'op_id' => $user->id,
                'question_id' => $question->id,
                'side_id' => $side->id,
            ]);
            $answers = factory(Answer::class, 2)->create([
                'op_id' => $user->id,
                'question_id' => $question->id,
                'side_id' => $side->id,
                'claim_id' => function () use ($user) {
                    return factory(Claim::class)->create([
                        'op_id' => $user->id,
                    ])->id;
                },
            ]);
        }

        dispatch_now(new UpdateQuestionStats($question));

        $this->assertEquals([
            'answers_count' => 6,
            'comments_count' => 1,
            'sides_count' => 3,
        ], $this->stats($question->fresh()));
    }
}
