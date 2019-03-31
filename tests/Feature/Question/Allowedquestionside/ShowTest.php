<?php

namespace Tests\Feature\Question\Allowedquestionside;

use App\Allowedquestionside;
use App\User;
use App\Side;
use App\Question;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side */
    protected $side;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->side = factory(Side::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->allowedquestionside = factory(Allowedquestionside::class)->create([
            'op_id' => $this->users[0]->id,
            'side_id' => $this->side->id,
            'question_id' => $this->question->id,
        ]);
    }

    public function testShowAllowedquestionsideAsUser()
    {
        $allowedquestionside = $this->allowedquestionside;
        $this->actingAs($this->users[0])
            ->getJson('/questions/'.$this->question->id.'/allowedquestionsides/'.$this->allowedquestionside->id)
            ->assertSuccessful()
            ->assertJson([
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
                'question' => [
                    'id' => $allowedquestionside->question->id,
                    'title' => $allowedquestionside->question->title,
                    'text'  => $allowedquestionside->question->text,
                    'op_id' => $allowedquestionside->question->op->id,
                    'op' => [
                        'id'     => $allowedquestionside->question->op->id,
                        'handle' => $allowedquestionside->question->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowAllowedquestionsideAsGuest()
    {
        $allowedquestionside = $this->allowedquestionside;
        $this->getJson('/questions/'.$this->question->id.'/allowedquestionsides/'.$this->allowedquestionside->id)
            ->assertSuccessful()
            ->assertJson([
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
                'question' => [
                    'id' => $allowedquestionside->question->id,
                    'title' => $allowedquestionside->question->title,
                    'text'  => $allowedquestionside->question->text,
                    'op_id' => $allowedquestionside->question->op->id,
                    'op' => [
                        'id'     => $allowedquestionside->question->op->id,
                        'handle' => $allowedquestionside->question->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
