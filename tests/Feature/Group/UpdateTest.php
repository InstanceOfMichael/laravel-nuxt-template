<?php

namespace Tests\Feature\Group;

use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Group */
    protected $group;
    /** @var \App\Group */
    protected $updatedGroup;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->user->id,
        ]);
        $q = factory(Group::class)->make();
        $this->updatedGroup = factory(Group::class)->make();
    }

    protected function getPayload(): array {
        return [
            'name' => $this->updatedGroup->name,
            'text'  => $this->updatedGroup->text,
        ];
    }

    public function testUpdateGroupAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/groups/'.$this->group->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->updatedGroup->name,
                'text'  => $this->updatedGroup->text,
                'op_id' => $this->group->op->id,
                'op' => [
                    'id'     => $this->group->op->id,
                    'handle' => $this->group->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateGroupPatchWithoutId()
    {
        $this->patchJson('/groups', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateGroupAsGuest()
    {
        $this->patchJson('/groups/'.$this->group->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateGroupEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/groups/'.$this->group->id, [])
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->group->name,
                'text'  => $this->group->text,
                'op_id' => $this->group->op->id,
                'op' => [
                    'id'     => $this->group->op->id,
                    'handle' => $this->group->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateGroupEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/groups/'.$this->group->id, [
                'name' => null,
                'text' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text must be a string."],
                    "name" => [
                        "The name must be a string.",
                        "The name must be at least 3 characters.",
                    ],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
