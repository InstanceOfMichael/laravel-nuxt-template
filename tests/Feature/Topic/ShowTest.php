<?php

namespace Tests\Feature\Topic;

use App\User;
use App\Topic;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Topic */
    protected $topic;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->topic = factory(Topic::class)->create();
    }

    public function testShowTopicAsUser()
    {
        $topic = $this->topic;
        $this->actingAs($this->user)
            ->getJson('/topics/'.$this->topic->id)
            ->assertStatus(200)
            ->assertJson([
                'id'    => $topic->id,
                'name'  => $topic->name,
                'text'  => $topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testShowTopicAsGuest()
    {
        $topic = $this->topic;
        $this->getJson('/topics/'.$this->topic->id)
            ->assertStatus(200)
            ->assertJson([
                'id'    => $topic->id,
                'name'  => $topic->name,
                'text'  => $topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
