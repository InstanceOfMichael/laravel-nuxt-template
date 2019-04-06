<?php

namespace Tests\Feature\Claim\Claimtopic;

use App\Claimtopic;
use App\Http\Middleware\Idempotency;
use App\User;
use App\Topic;
use App\Claim;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic */
    protected $topic;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Claimtopic */
    protected $claimtopic;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claim->setRelation('op', $this->users[1]);
        $this->topics = factory(Topic::class, 2)->create();

        $this->claimtopics = $this->topics->map(function (Topic $topic) {
            return $this->claimtopics = factory(Claimtopic::class)->make([
                'op_id' => $this->users[0]->id,
                'topic_id' => $topic->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[0])
            ->setRelation('claim', $this->claim)
            ->setRelation('topic', $topic);
        });
    }

    protected function getPayload(): array {
        return [
            'topic_id' => $this->claimtopics[0]->topic_id,
        ];
    }

    protected function getBulkPayload(): array {
        return [
            'topic_id_list' => $this->claimtopics->pluck('topic_id')->values()->all(),
        ];
    }

    public function testStoreClaimtopicAsUser()
    {
        $claimtopic = $this->claimtopics[0];
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimtopics', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $claimtopic->id,
                'op_id' => $claimtopic->op->id,
                'topic_id' => $claimtopic->topic_id,
                'claim_id' => $claimtopic->claim_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreClaimtopicAsUserIdempotent()
    {
        $claimtopic = $this->claimtopics[0];
        $r1 = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimtopics', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            // ->assertStatus(201)
            ->assertJson([
                // 'id' => $claimtopic->id,
                'op_id' => $claimtopic->op->id,
                'topic_id' => $claimtopic->topic_id,
                'claim_id' => $claimtopic->claim_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r2 = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimtopics', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $claimtopic->id,
                'op_id' => $claimtopic->op->id,
                'topic_id' => $claimtopic->topic_id,
                'claim_id' => $claimtopic->claim_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimtopics', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["This topic is already associated with this claim."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreClaimtopicAsGuest()
    {
        $this->postJson('/claims/'.$this->claim->id.'/claimtopics', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreClaimtopicEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimtopics', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["The topic id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreClaimtopicEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimtopics', [
                "topic_id" => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["The topic id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreClaimtopicEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimtopics', [
                "topic_id" => 0,
            ])
            ->assertStatus(422);
    }
}
