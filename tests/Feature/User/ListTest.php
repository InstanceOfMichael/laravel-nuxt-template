<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
    }

    public function testListUsersAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/users')
            ->assertStatus(405)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListUsersAsGuest()
    {
        $this->getJson('/users')
            ->assertStatus(401)
            ->assertDontExposeUserEmails($this->users);
    }
}
