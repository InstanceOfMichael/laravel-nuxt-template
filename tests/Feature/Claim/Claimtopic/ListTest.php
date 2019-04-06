<?php

namespace Tests\Feature\Claim\Claimtopic;

use App\Claimtopic;
use App\User;
use App\Topic;
use App\Claim;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic[] */
    protected $topics;
    /** @var \App\Claimtopic[] */
    protected $claimtopics;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->topics = collect([
            factory(Topic::class)->create(),
            factory(Topic::class)->create(),
            factory(Topic::class)->create(),
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[3]->id,
        ]);
        $this->claimtopics = collect([
            factory(Claimtopic::class)->create([
                'op_id' => $this->users[6]->id,
                'topic_id' => $this->topics[0]->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('topic', $this->topics[0])
            ->setRelation('claim', $this->claim),
            factory(Claimtopic::class)->create([
                'op_id' => $this->users[7]->id,
                'topic_id' => $this->topics[1]->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('topic', $this->topics[1])
            ->setRelation('claim', $this->claim),
            factory(Claimtopic::class)->create([
                'op_id' => $this->users[8]->id,
                'topic_id' => $this->topics[2]->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('topic', $this->topics[2])
            ->setRelation('claim', $this->claim),
        ])->sortByDesc('id')->values();
    }

    public function testListClaimtopicAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/claims/'.$this->claim->id.'/claimtopics')
            ->assertStatus(200)
            ->assertJson($this->claimtopics->map(function (Claimtopic $claimtopic):array {
                return [
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
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListClaimtopicAsGuest()
    {
        $this->getJson('/claims/'.$this->claim->id.'/claimtopics')
            ->assertStatus(200)
            ->assertJson($this->claimtopics->map(function (Claimtopic $claimtopic):array {
                return [
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
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
