<?php

namespace Tests\Feature\Group\Groupmembership;

use App\Groupmembership;
use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Group[] */
    protected $groups;
    /** @var \App\Groupmembership[] */
    protected $groupmemberships;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->group = factory(Group::class)->create([ 'op_id' => $this->users[3]->id ]);
        $this->groupmemberships = collect([
            factory(Groupmembership::class)->create([
                'user_id' => $this->users[6]->id,
                'user_id' => $this->users[0]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('user', $this->users[0])
            ->setRelation('group', $this->group),
            factory(Groupmembership::class)->create([
                'user_id' => $this->users[7]->id,
                'user_id' => $this->users[1]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('user', $this->users[1])
            ->setRelation('group', $this->group),
            factory(Groupmembership::class)->create([
                'user_id' => $this->users[8]->id,
                'user_id' => $this->users[2]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('user', $this->users[2])
            ->setRelation('group', $this->group),
        ])->sortByDesc('id')->values();
    }

    public function testListGroupmembershipAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupmemberships')
            ->assertStatus(200)
            ->assertJson($this->groupmemberships->map(function (Groupmembership $groupmembership):array {
                return [
                    'id' => $groupmembership->id,
                    'user_id' => $groupmembership->user_id,
                    'user' => [
                        'id' => $groupmembership->user->id,
                        'name' => $groupmembership->user->name,
                    ],
                    'group_id' => $groupmembership->group_id,
                    'group' => [
                        'id' => $groupmembership->group->id,
                        'name' => $groupmembership->group->name,
                    ],
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListGroupmembershipAsGuest()
    {
        $this->getJson('/groups/'.$this->group->id.'/groupmemberships')
            ->assertStatus(200)
            ->assertJson($this->groupmemberships->map(function (Groupmembership $groupmembership):array {
                return [
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
                    ],
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
