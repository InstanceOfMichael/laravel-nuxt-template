<?php

namespace Tests\Feature\Question\Allowedquestionside;

use App\Allowedquestionside;
use App\User;
use App\Side;
use App\Question;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side[] */
    protected $questions;
    /** @var \App\Question[] */
    protected $sides;
    /** @var \App\Allowedquestionside[] */
    protected $allowedquestionsides;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->sides = collect([
            factory(Side::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
        $this->question = factory(Question::class)->create([ 'op_id' => $this->users[3]->id ]);
        $this->allowedquestionsides = collect([
            factory(Allowedquestionside::class)->create([
                'op_id' => $this->users[6]->id,
                'side_id' => $this->sides[0]->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('side', $this->sides[0])
            ->setRelation('question', $this->question),
            factory(Allowedquestionside::class)->create([
                'op_id' => $this->users[7]->id,
                'side_id' => $this->sides[1]->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('side', $this->sides[1])
            ->setRelation('question', $this->question),
            factory(Allowedquestionside::class)->create([
                'op_id' => $this->users[8]->id,
                'side_id' => $this->sides[2]->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('side', $this->sides[2])
            ->setRelation('question', $this->question),
        ])->sortByDesc('id')->values();
    }

    public function testListAllowedquestionsideAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/questions/'.$this->question->id.'/allowedquestionsides')
            ->assertSuccessful()
            ->assertJson($this->allowedquestionsides->map(function (Allowedquestionside $allowedquestionside):array {
                return [
                    'id' => $allowedquestionside->id,
                    'op_id' => $allowedquestionside->op->id,
                    'op' => [
                        'id'     => $allowedquestionside->op->id,
                        'handle' => $allowedquestionside->op->handle,
                    ],
                    'side_id' => $allowedquestionside->side_id,
                    'side' => [
                        'id' => $allowedquestionside->side->id,
                        'name' => $allowedquestionside->side->name,
                        'text'  => $allowedquestionside->side->text,
                        'op_id' => $allowedquestionside->side->op->id,
                        'op' => [
                            'id'     => $allowedquestionside->side->op->id,
                            'handle' => $allowedquestionside->side->op->handle,
                        ],
                    ],
                    'question_id' => $allowedquestionside->question_id,
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListAllowedquestionsideAsGuest()
    {
        $this->getJson('/questions/'.$this->question->id.'/allowedquestionsides')
            ->assertSuccessful()
            ->assertJson($this->allowedquestionsides->map(function (Allowedquestionside $allowedquestionside):array {
                return [
                    'id' => $allowedquestionside->id,
                    'op_id' => $allowedquestionside->op->id,
                    'op' => [
                        'id'     => $allowedquestionside->op->id,
                        'handle' => $allowedquestionside->op->handle,
                    ],
                    'side_id' => $allowedquestionside->side_id,
                    'side' => [
                        'id' => $allowedquestionside->side->id,
                        'name' => $allowedquestionside->side->name,
                        'text'  => $allowedquestionside->side->text,
                        'op_id' => $allowedquestionside->side->op->id,
                        'op' => [
                            'id'     => $allowedquestionside->side->op->id,
                            'handle' => $allowedquestionside->side->op->handle,
                        ],
                    ],
                    'question_id' => $allowedquestionside->question_id,
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
