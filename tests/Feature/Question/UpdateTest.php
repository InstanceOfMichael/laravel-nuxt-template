<?php

namespace Tests\Feature\Question;

use App\User;
use App\Question;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Question */
    protected $updatedQuestion;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->user->id,
        ]);
        $q = factory(Question::class)->make();
        $this->updatedQuestion = factory(Question::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedQuestion->title,
            'text'  => $this->updatedQuestion->text,
            'sides_type'  => $this->updatedQuestion->sides_type,
        ];
    }

    public function testUpdateQuestionAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/questions/'.$this->question->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->updatedQuestion->title,
                'text'  => $this->updatedQuestion->text,
                'sides_type'  => $this->updatedQuestion->sides_type,
                'op_id' => $this->question->op->id,
                'op' => [
                    'id'     => $this->question->op->id,
                    'handle' => $this->question->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateQuestionPatchWithoutId()
    {
        $this->patchJson('/questions', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateQuestionAsGuest()
    {
        $this->patchJson('/questions/'.$this->question->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateQuestionEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/questions/'.$this->question->id, [])
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->question->title,
                'text'  => $this->question->text,
                'sides_type'  => $this->question->sides_type,
                'op_id' => $this->question->op->id,
                'op' => [
                    'id'     => $this->question->op->id,
                    'handle' => $this->question->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateQuestionEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/questions/'.$this->question->id, [
                'title' => null,
                'text' => null,
                'sides_type' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text must be a string."],
                    "title" => [
                        "The title must be a string.",
                        "The title has to end with: ?",
                        "The title must be at least 9 characters.",
                    ],
                    "sides_type" => ["The selected sides type is invalid."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateQuestionTitleRequiresQuestionMark()
    {
        $this->actingAs($this->user)
            ->patchJson('/questions/'.$this->question->id, [
                'title' => 'string without question mark',
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "title" => [
                        "The title has to end with: ?",
                    ],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
