<?php

namespace Tests\Feature\User;

use App\User;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\User */
    protected $updatedUser;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 2)->create();
        $this->updatedUser = factory(User::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedUser->title,
            'text'  => $this->updatedUser->text,
        ];
    }

    public function testUpdateUserAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/users/'.$this->users[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->users[0]->id,
                'handle' => $this->users[0]->handle,
                'name' => $this->users[0]->name,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateUserAsOther()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/users/'.$this->users[1]->id, $this->getPayload())
            ->assertStatus(403)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateUserPatchWithoutId()
    {
        $this->patchJson('/users', $this->getPayload())
            ->assertStatus(405)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateUserAsGuest()
    {
        $this->patchJson('/users/'.$this->users[0]->id, $this->getPayload())
            ->assertStatus(401)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateUserEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/users/'.$this->users[0]->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->users[0]->id,
                'handle' => $this->users[0]->handle,
                'name' => $this->users[0]->name,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateUserEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/users/'.$this->users[0]->id, [
                'name' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => ["The name must be a string.","The name must be at least 3 characters."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
