<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class UsersTest extends TestCase
{
    /** @var \App\User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 2)->create();
    }

    public function testShowUserAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/users/'.$this->users[1]->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'handle' => $this->users[1]->handle,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowUserAsGuest()
    {
        $this->getJson('/users/'.$this->users[1]->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                'handle' => $this->users[1]->handle,
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
