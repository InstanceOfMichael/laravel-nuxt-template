<?php

namespace Tests\Feature\Answer;

use App\Answer;
use App\User;
use App\Claim;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $claim;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->answer = factory(Answer::class)->create([
            'op_id' => $this->users[0]->id,
            'claim_id' => $this->claim->id,
            'question_id' => $this->question->id,
        ]);
    }

    public function testShowClaimAsUser()
    {
        $answer = $this->answer;
        $this->actingAs($this->users[0])
            ->getJson('/answers/'.$this->claim->id)
            ->assertSuccessful()
            ->assertJson([
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
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowClaimAsGuest()
    {
        $answer = $this->answer;
        $this->getJson('/answers/'.$this->claim->id)
            ->assertSuccessful()
            ->assertJson([
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
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
