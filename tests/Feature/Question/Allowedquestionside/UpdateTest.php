<?php

namespace Tests\Feature\Question\Allowedquestionside;

use App\Allowedquestionside;
use App\User;
use App\Side;
use App\Question;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side */
    protected $side;
    /** @var \App\Allowedquestionside */
    protected $allowedquestionside;
    /** @var \App\Allowedquestionside */
    protected $updatedAllowedquestionside;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->side = factory(Side::class)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->allowedquestionside = factory(Allowedquestionside::class)->create([
            'op_id' => $this->users[0]->id,
            'side_id' => $this->side->id,
            'question_id' => $this->question->id,
        ]);
        $this->updatedAllowedquestionside = factory(Allowedquestionside::class)->make([
            'op_id' => $this->users[0]->id,
        ]);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedAllowedquestionside->title,
            'text'  => $this->updatedAllowedquestionside->text,
        ];
    }

    public function testUpdateAllowedquestionsideAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/questions/'.$this->question->id.'/allowedquestionsides/'.$this->allowedquestionside->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->allowedquestionside->id,
                'op_id' => $this->allowedquestionside->op->id,
                'side_id' => $this->allowedquestionside->side_id,
                'question_id' => $this->allowedquestionside->question->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateAllowedquestionsidePatchWithoutId()
    {
        $this->patchJson('/questions/'.$this->question->id.'/allowedquestionsides', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateAllowedquestionsideAsGuest()
    {
        $this->patchJson('/questions/'.$this->question->id.'/allowedquestionsides/'.$this->allowedquestionside->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateAllowedquestionsideEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/questions/'.$this->question->id.'/allowedquestionsides/'.$this->allowedquestionside->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->allowedquestionside->id,
                'op_id' => $this->allowedquestionside->op->id,
                'side_id' => $this->allowedquestionside->side_id,
                'question_id' => $this->allowedquestionside->question->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateAllowedquestionsideEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/questions/'.$this->question->id.'/allowedquestionsides/'.$this->allowedquestionside->id, [
                'side_id' => null,
                'question_id' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["The side id field is required."],
                    "question_id" => ["The question id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
