<?php

namespace Tests\Feature\Question;

use App\User;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class StoreTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Question */
    protected $question;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->question = factory(Question::class)->make([
            // 'op_id' => $this->user->id,
        ]);
        $this->question->setRelation('op', $this->user);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->question->title,
            'text'  => $this->question->text,
            'sides_type' => $this->question->sides_type,
        ];
    }

    public function testStoreQuestionAsUser()
    {
        $this->actingAs($this->user)
            ->postJson('/questions', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'title' => $this->question->title,
                'text'  => $this->question->text,
                'sides_type' => $this->question->sides_type,
                'op_id' => $this->question->op->id,
                'op' => [
                    'id'     => $this->question->op->id,
                    'handle' => $this->question->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreQuestionAsGuest()
    {
        $this->postJson('/questions', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreQuestionEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/questions', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "sides_type" => ["The sides type field is required."],
                    // "text" => ["The text field is required."],
                    "title" => ["The title field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreQuestionEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/questions', [
                'text' => null,
                'title' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "sides_type" => ["The sides type field is required."],
                    "text" => ["The text must be a string."],
                    "title" => ["The title field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
