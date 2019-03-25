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
            'title' => $this->updatedGroup->title,
            'text'  => $this->updatedGroup->text,
            'sides_type'  => $this->updatedGroup->sides_type,
        ];
    }

    public function testUpdateGroupAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/groups/'.$this->group->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->updatedGroup->title,
                'text'  => $this->updatedGroup->text,
                'sides_type'  => $this->updatedGroup->sides_type,
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
                'title' => $this->group->title,
                'text'  => $this->group->text,
                'sides_type'  => $this->group->sides_type,
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
                'title' => null,
                'text' => null,
                'sides_type' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text must be a string."],
                    "title" => [
                        "The title must be a string.",
                        "The title has to end with: ?",
                        "The title must be at least 9 characters.",
                    ],
                    "sides_type" => ["The selected sides type is invalid."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateGroupTitleRequiresGroupMark()
    {
        $this->actingAs($this->user)
            ->patchJson('/groups/'.$this->group->id, [
                'title' => 'string without group mark',
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "title" => [
                        "The title has to end with: ?",
                    ],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
