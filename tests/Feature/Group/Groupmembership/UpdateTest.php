<?php

namespace Tests\Feature\Group\Groupmembership;

use App\Groupmembership;
use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Groupmembership */
    protected $groupmembership;
    /** @var \App\Groupmembership */
    protected $updatedGroupmembership;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->groupmembership = factory(Groupmembership::class)->create([
            'user_id' => $this->users[0]->id,
            'group_id' => $this->group->id,
        ]);
        $this->updatedGroupmembership = factory(Groupmembership::class)->make([
        ]);
    }

    protected function getPayload(): array {
        return [
        ];
    }

    public function testUpdateGroupmembershipAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->groupmembership->id,
                'user_id' => $this->groupmembership->user_id,
                'group_id' => $this->groupmembership->group->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateGroupmembershipPatchWithoutId()
    {
        $this->patchJson('/groups/'.$this->group->id.'/groupmemberships', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateGroupmembershipAsGuest()
    {
        $this->patchJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateGroupmembershipEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->groupmembership->id,
                'user_id' => $this->groupmembership->user_id,
                'group_id' => $this->groupmembership->group->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateGroupmembershipEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id, [
                'user_id' => null,
                'group_id' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "user_id" => ["The user id field is required."],
                    "group_id" => ["The group id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
