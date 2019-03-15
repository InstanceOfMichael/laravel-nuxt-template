<?php

namespace Tests\Feature\Question;

use App\User;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\Idempotency;

/**
 * @group store
 */
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
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreQuestionAsUserIdempotent()
    {
        $r1 = $this->actingAs($this->user)
            ->postJson('/questions', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'title' => $this->question->title,
                'text'  => $this->question->text,
                'sides_type' => $this->question->sides_type,
                'op_id' => $this->question->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r2 = $this->actingAs($this->user)
            ->postJson('/questions', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'title' => $this->question->title,
                'text'  => $this->question->text,
                'sides_type' => $this->question->sides_type,
                'op_id' => $this->question->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r3 = $this->actingAs($this->user)
            ->postJson('/questions', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'title' => $this->question->title,
                'text'  => $this->question->text,
                'sides_type' => $this->question->sides_type,
                'op_id' => $this->question->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
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

    public function testStoreQuestionTitleRequiresQuestionMark()
    {
        $this->actingAs($this->user)
            ->postJson('/questions', [
                'title' => 'string without question mark',
            ]+$this->getPayload())
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
