<?php

namespace Tests\Feature\Claim\Claimtopic;

use App\Claimtopic;
use App\User;
use App\Topic;
use App\Claim;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic */
    protected $topic;
    /** @var \App\Claimtopic */
    protected $claimtopic;
    /** @var \App\Claimtopic */
    protected $updatedClaimtopic;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->topic = factory(Topic::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->claimtopic = factory(Claimtopic::class)->create([
            'op_id' => $this->users[0]->id,
            'topic_id' => $this->topic->id,
            'claim_id' => $this->claim->id,
        ]);
        $this->updatedClaimtopic = factory(Claimtopic::class)->make([
            'op_id' => $this->users[0]->id,
        ]);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedClaimtopic->title,
            'text'  => $this->updatedClaimtopic->text,
        ];
    }

    public function testUpdateClaimtopicAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claims/'.$this->claim->id.'/claimtopics/'.$this->claimtopic->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->claimtopic->id,
                'op_id' => $this->claimtopic->op->id,
                'topic_id' => $this->claimtopic->topic_id,
                'claim_id' => $this->claimtopic->claim->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateClaimtopicPatchWithoutId()
    {
        $this->patchJson('/claims/'.$this->claim->id.'/claimtopics', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateClaimtopicAsGuest()
    {
        $this->patchJson('/claims/'.$this->claim->id.'/claimtopics/'.$this->claimtopic->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateClaimtopicEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claims/'.$this->claim->id.'/claimtopics/'.$this->claimtopic->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->claimtopic->id,
                'op_id' => $this->claimtopic->op->id,
                'topic_id' => $this->claimtopic->topic_id,
                'claim_id' => $this->claimtopic->claim->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateClaimtopicEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/claims/'.$this->claim->id.'/claimtopics/'.$this->claimtopic->id, [
                'topic_id' => null,
                'claim_id' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["The topic id field is required."],
                    "claim_id" => ["The claim id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
