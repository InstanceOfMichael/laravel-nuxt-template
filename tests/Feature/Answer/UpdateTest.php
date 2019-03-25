<?php

namespace Tests\Feature\Answer;

use App\Answer;
use App\User;
use App\Claim;
use App\Question;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Answer */
    protected $answer;
    /** @var \App\Answer */
    protected $updatedAnswer;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->answer = factory(Answer::class)->create([
            'op_id' => $this->users[0]->id,
            'claim_id' => $this->claim->id,
            'question_id' => $this->question->id,
        ]);
        $this->updatedAnswer = factory(Answer::class)->make([
            'op_id' => $this->users[0]->id,
        ]);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedAnswer->title,
            'text'  => $this->updatedAnswer->text,
        ];
    }

    public function testUpdateAnswerAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/answers/'.$this->answer->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->answer->id,
                'op_id' => $this->answer->op->id,
                'claim_id' => $this->answer->claim_id,
                'question_id' => $this->answer->question->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateAnswerPatchWithoutId()
    {
        $this->patchJson('/answers', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateAnswerAsGuest()
    {
        $this->patchJson('/answers/'.$this->answer->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateAnswerEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/answers/'.$this->answer->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->answer->id,
                'op_id' => $this->answer->op->id,
                'claim_id' => $this->answer->claim_id,
                'question_id' => $this->answer->question->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateAnswerEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/answers/'.$this->answer->id, [
                'claim_id' => null,
                'question_id' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "claim_id" => ["The claim id field is required."],
                    "question_id" => ["The question id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
