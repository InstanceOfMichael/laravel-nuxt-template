<?php

namespace Tests\Feature\Answer;

use App\Answer;
use App\Http\Middleware\Idempotency;
use App\User;
use App\Claim;
use App\Question;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Answer */
    protected $answer;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->question->setRelation('op', $this->users[1]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->claim->setRelation('op', $this->users[2]);
        $this->answer = factory(Answer::class)->make([
            'op_id' => $this->users[0]->id,
            'claim_id' => $this->claim->id,
            'question_id' => $this->question->id,
        ]);
        $this->answer->setRelation('op', $this->users[0]);
        $this->answer->setRelation('question', $this->question);
        $this->answer->setRelation('claim', $this->claim);
    }

    protected function getPayload(): array {
        return [
            'claim_id' => $this->answer->claim_id,
            'question_id'  => $this->answer->question_id,
        ];
    }

    public function testStoreAnswerAsUser()
    {
        $answer = $this->answer;
        $this->actingAs($this->users[0])
            ->postJson('/answers', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $answer->id,
                'op_id' => $answer->op->id,
                'claim_id' => $answer->claim_id,
                'question_id' => $answer->question_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreAnswerAsUserIdempotent()
    {
        $answer = $this->answer;
        $r1 = $this->actingAs($this->users[0])
            ->postJson('/answers', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $answer->id,
                'op_id' => $answer->op->id,
                'claim_id' => $answer->claim_id,
                'question_id' => $answer->question_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r2 = $this->actingAs($this->users[0])
            ->postJson('/answers', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $answer->id,
                'op_id' => $answer->op->id,
                'claim_id' => $answer->claim_id,
                'question_id' => $answer->question_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/answers', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "claim_id" => ["This claim is already associated with this question."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreAnswerAsGuest()
    {
        $this->postJson('/answers', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreAnswerEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/answers', [])
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

    public function testStoreAnswerEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/answers', [
                "claim_id" => null,
                "question_id" => null,
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

    public function testStoreAnswerEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/answers', [
                "claim_id" => 0,
                "question_id" => 0,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "claim_id" => ["The selected claim id is invalid."],
                    "question_id" => ["The selected question id is invalid."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

}
