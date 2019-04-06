<?php

namespace Tests\Feature\Claim\Claimtopic;

use App\Claimtopic;
use App\User;
use App\Topic;
use App\Claim;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic */
    protected $topic;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->topic = factory(Topic::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claimtopic = factory(Claimtopic::class)->create([
            'op_id' => $this->users[0]->id,
            'topic_id' => $this->topic->id,
            'claim_id' => $this->claim->id,
        ]);
    }

    public function testShowClaimtopicAsUser()
    {
        $claimtopic = $this->claimtopic;
        $this->actingAs($this->users[0])
            ->getJson('/claims/'.$this->claim->id.'/claimtopics/'.$this->claimtopic->id)
            ->assertStatus(200)
            ->assertJson([
                'id' => $claimtopic->id,
                'op_id' => $claimtopic->op->id,
                'op' => [
                    'id'     => $claimtopic->op->id,
                    'handle' => $claimtopic->op->handle,
                ],
                'topic_id' => $claimtopic->topic_id,
                'topic' => [
                    'id' => $claimtopic->topic->id,
                    'name' => $claimtopic->topic->name,
                    'text'  => $claimtopic->topic->text,
                ],
                'claim_id' => $claimtopic->claim_id,
                'claim' => [
                    'id' => $claimtopic->claim->id,
                    'title' => $claimtopic->claim->title,
                    'text'  => $claimtopic->claim->text,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowClaimtopicAsGuest()
    {
        $claimtopic = $this->claimtopic;
        $this->getJson('/claims/'.$this->claim->id.'/claimtopics/'.$this->claimtopic->id)
            ->assertStatus(200)
            ->assertJson([
                'id' => $claimtopic->id,
                'op_id' => $claimtopic->op->id,
                'op' => [
                    'id'     => $claimtopic->op->id,
                    'handle' => $claimtopic->op->handle,
                ],
                'topic_id' => $claimtopic->topic_id,
                'topic' => [
                    'id' => $claimtopic->topic->id,
                    'name' => $claimtopic->topic->name,
                    'text'  => $claimtopic->topic->text,
                ],
                'claim_id' => $claimtopic->claim_id,
                'claim' => [
                    'id' => $claimtopic->claim->id,
                    'title' => $claimtopic->claim->title,
                    'text'  => $claimtopic->claim->text,
                    'op_id' => $claimtopic->claim->op->id,
                    'op' => [
                        'id'     => $claimtopic->claim->op->id,
                        'handle' => $claimtopic->claim->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
