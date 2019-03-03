<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;

    public function setUp()
    {
        parent::setUp();
        $this->users = factory(User::class, 2)->create();
    }

    protected function getPayload(): array {
        $user = factory(User::class)->make();
        return [
            'name' => $user->name,
            'email' => $user->email,
            'handle' => $user->handle,
        ];
    }

    public function testStoreUserAsUser()
    {
        $this->actingAs($this->users[0])
            ->postJson('/users', $this->getPayload())
            ->assertStatus(405)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreUserAsGuest()
    {
        $this->postJson('/users', $this->getPayload())
            ->assertStatus(401)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreUserEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/users', [])
            ->assertStatus(405)
            ->assertDontExposeUserEmails($this->users);
    }
}
