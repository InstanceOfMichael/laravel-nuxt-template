<?php

namespace Tests\Feature\Group\Groupmembership;

use App\Groupmembership;
use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->user = factory(User::class)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->groupmembership = factory(Groupmembership::class)->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
        ]);
    }

    public function testShowGroupmembershipAsUser()
    {
        $groupmembership = $this->groupmembership;
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id)
            ->assertSuccessful()
            ->assertJson([
                'id' => $groupmembership->id,
                'user_id' => $groupmembership->user_id,
                'user' => [
                    'id' => $groupmembership->user->id,
                    'handle'  => $groupmembership->user->handle,
                    'name' => $groupmembership->user->name,
                ],
                'group_id' => $groupmembership->group_id,
                'group' => [
                    'id' => $groupmembership->group->id,
                    'name' => $groupmembership->group->name,
                    'text'  => $groupmembership->group->text,
                    'op_id' => $groupmembership->group->op->id,
                    'op' => [
                        'id'     => $groupmembership->group->op->id,
                        'handle' => $groupmembership->group->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowGroupmembershipAsGuest()
    {
        $groupmembership = $this->groupmembership;
        $this->getJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id)
            ->assertSuccessful()
            ->assertJson([
                'id' => $groupmembership->id,
                'user_id' => $groupmembership->user_id,
                'user' => [
                    'id' => $groupmembership->user->id,
                    'handle'  => $groupmembership->user->handle,
                    'name' => $groupmembership->user->name,
                ],
                'group_id' => $groupmembership->group_id,
                'group' => [
                    'id' => $groupmembership->group->id,
                    'name' => $groupmembership->group->name,
                    'text'  => $groupmembership->group->text,
                    'op_id' => $groupmembership->group->op->id,
                    'op' => [
                        'id'     => $groupmembership->group->op->id,
                        'handle' => $groupmembership->group->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
