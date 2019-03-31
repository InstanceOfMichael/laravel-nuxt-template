<?php

namespace Tests\Feature\Answer;

use App\Answer;
use App\User;
use App\Claim;
use App\Question;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim[] */
    protected $questions;
    /** @var \App\Question[] */
    protected $claims;
    /** @var \App\Answer[] */
    protected $answers;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->claims = collect([
            factory(Claim::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
        $this->questions = collect([
            factory(Question::class)->create([ 'op_id' => $this->users[3]->id ]),
            factory(Question::class)->create([ 'op_id' => $this->users[4]->id ]),
            factory(Question::class)->create([ 'op_id' => $this->users[5]->id ]),
        ]);
        $this->answers = collect([
            factory(Answer::class)->create([
                'op_id' => $this->users[6]->id,
                'claim_id' => $this->claims[0]->id,
                'question_id' => $this->questions[0]->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('claim', $this->claims[0])
            ->setRelation('question', $this->questions[0]),
            factory(Answer::class)->create([
                'op_id' => $this->users[7]->id,
                'claim_id' => $this->claims[1]->id,
                'question_id' => $this->questions[1]->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('claim', $this->claims[1])
            ->setRelation('question', $this->questions[1]),
            factory(Answer::class)->create([
                'op_id' => $this->users[8]->id,
                'claim_id' => $this->claims[2]->id,
                'question_id' => $this->questions[2]->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('claim', $this->claims[2])
            ->setRelation('question', $this->questions[2]),
        ])->sortByDesc('id')->values();
    }

    public function testListAnswerAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/answers')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->answers->map(function (Answer $answer):array {
                    return [
                        'id' => $answer->id,
                        'op_id' => $answer->op->id,
                        'op' => [
                            'id'     => $answer->op->id,
                            'handle' => $answer->op->handle,
                        ],
                        'claim_id' => $answer->claim_id,
                        'claim' => [
                            'id' => $answer->claim->id,
                            'title' => $answer->claim->title,
                            'text'  => $answer->claim->text,
                            'op_id' => $answer->claim->op->id,
                            'op' => [
                                'id'     => $answer->claim->op->id,
                                'handle' => $answer->claim->op->handle,
                            ],
                        ],
                        'question_id' => $answer->question_id,
                        'question' => [
                            'id' => $answer->question->id,
                            'title' => $answer->question->title,
                            'text'  => $answer->question->text,
                            'op_id' => $answer->question->op->id,
                            'op' => [
                                'id'     => $answer->question->op->id,
                                'handle' => $answer->question->op->handle,
                            ],
                        ],
                    ];
                })->all(),
                'total' => $this->answers->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListAnswerAsGuest()
    {
        $this->getJson('/answers')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->answers->map(function (Answer $answer):array {
                    return [
                        'id' => $answer->id,
                        'op_id' => $answer->op->id,
                        'op' => [
                            'id'     => $answer->op->id,
                            'handle' => $answer->op->handle,
                        ],
                        'claim_id' => $answer->claim_id,
                        'claim' => [
                            'id' => $answer->claim->id,
                            'title' => $answer->claim->title,
                            'text'  => $answer->claim->text,
                            'op_id' => $answer->claim->op->id,
                            'op' => [
                                'id'     => $answer->claim->op->id,
                                'handle' => $answer->claim->op->handle,
                            ],
                        ],
                        'question_id' => $answer->question_id,
                        'question' => [
                            'id' => $answer->question->id,
                            'title' => $answer->question->title,
                            'text'  => $answer->question->text,
                            'op_id' => $answer->question->op->id,
                            'op' => [
                                'id'     => $answer->question->op->id,
                                'handle' => $answer->question->op->handle,
                            ],
                        ],
                    ];
                })->all(),
                'total' => $this->answers->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
