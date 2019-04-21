<?php

namespace Tests\Unit\Jobs;

use App\Allowedquestionside;
use App\Answer;
use App\Claim;
use App\Comment;
use App\Jobs\UpdateClaimStats;
use App\Question;
use App\Side;
use App\User;
use Tests\TestCase;

class UpdateClaimStatsTest extends TestCase
{

    private function stats (Claim $claim) {
        return [
            'answers_count' => $claim->answers_count,
            'comments_count' => $claim->comments_count,
            'sides_count' => $claim->sides_count,
        ];
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClaimWithNoRelations()
    {
        $user = factory(User::class)->create();
        $claim = factory(Claim::class)->create([
            'op_id' => $user->id,
        ]);

        dispatch_now(new UpdateClaimStats($claim));

        $this->assertEquals([
            'answers_count' => 0,
            'comments_count' => 0,
            'sides_count' => 0,
        ], $this->stats($claim->fresh()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClaimWithAllRelationsSideTypeAllow()
    {
        $user = factory(User::class)->create();
        $claim = factory(Claim::class)->create([
            'op_id' => $user->id,
        ]);

        $claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $user->id,
        ]));

        $sides = factory(Side::class, 3)->create();
        foreach($sides as $side) {
            $answers = factory(Answer::class, 2)->create([
                'op_id' => $user->id,
                'question_id' => function() use ($user, $side) {
                    $question = factory(Question::class)->create([
                        'sides_type' => Side::TYPE_ALLOW,
                        'op_id' => $user->id,
                    ]);
                    $allowedquestionsides = factory(Allowedquestionside::class)->create([
                        'op_id' => $user->id,
                        'question_id' => $question->id,
                        'side_id' => $side->id,
                    ]);
                    return $question->id;
                },
                'side_id' => $side->id,
                'claim_id' => $claim->id,
            ]);
        }

        dispatch_now(new UpdateClaimStats($claim));

        $this->assertEquals([
            'answers_count' => 6,
            'comments_count' => 1,
            'sides_count' => 3,
        ], $this->stats($claim->fresh()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClaimWithAllRelationsSideTypeAny()
    {
        $user = factory(User::class)->create();
        $claim = factory(Claim::class)->create([
            'op_id' => $user->id,
        ]);

        $claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $user->id,
        ]));

        $sides = factory(Side::class, 3)->create();
        foreach($sides as $side) {
            $answers = factory(Answer::class, 2)->create([
                'op_id' => $user->id,
                'question_id' => function() use ($user, $side) {
                    $question = factory(Question::class)->create([
                        'sides_type' => Side::TYPE_ANY,
                        'op_id' => $user->id,
                    ]);
                    $allowedquestionsides = factory(Allowedquestionside::class)->create([
                        'op_id' => $user->id,
                        'question_id' => $question->id,
                        'side_id' => $side->id,
                    ]);
                    return $question->id;
                },
                'side_id' => $side->id,
                'claim_id' => $claim->id,
            ]);
        }

        dispatch_now(new UpdateClaimStats($claim));

        $this->assertEquals([
            'answers_count' => 6,
            'comments_count' => 1,
            'sides_count' => 3,
        ], $this->stats($claim->fresh()));
    }
}
