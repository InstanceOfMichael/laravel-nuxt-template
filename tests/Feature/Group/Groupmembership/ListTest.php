<?php

namespace Tests\Feature\Group\Groupmembership;

use App\Groupmembership;
use App\User;
use App\Side;
use App\Group;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side[] */
    protected $groups;
    /** @var \App\Group[] */
    protected $sides;
    /** @var \App\Groupmembership[] */
    protected $groupmemberships;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->sides = collect([
            factory(Side::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
        $this->group = factory(Group::class)->create([ 'op_id' => $this->users[3]->id ]);
        $this->groupmemberships = collect([
            factory(Groupmembership::class)->create([
                'op_id' => $this->users[6]->id,
                'side_id' => $this->sides[0]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('side', $this->sides[0])
            ->setRelation('group', $this->group),
            factory(Groupmembership::class)->create([
                'op_id' => $this->users[7]->id,
                'side_id' => $this->sides[1]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('side', $this->sides[1])
            ->setRelation('group', $this->group),
            factory(Groupmembership::class)->create([
                'op_id' => $this->users[8]->id,
                'side_id' => $this->sides[2]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('side', $this->sides[2])
            ->setRelation('group', $this->group),
        ])->sortByDesc('id')->values();
    }

    public function testListGroupmembershipAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupmemberships')
            ->assertSuccessful()
            ->assertJson($this->groupmemberships->map(function (Groupmembership $groupmembership):array {
                return [
                    'id' => $groupmembership->id,
                    'op_id' => $groupmembership->op->id,
                    'op' => [
                        'id'     => $groupmembership->op->id,
                        'handle' => $groupmembership->op->handle,
                    ],
                    'side_id' => $groupmembership->side_id,
                    'side' => [
                        'id' => $groupmembership->side->id,
                        'name' => $groupmembership->side->name,
                        'text'  => $groupmembership->side->text,
                        'op_id' => $groupmembership->side->op->id,
                        'op' => [
                            'id'     => $groupmembership->side->op->id,
                            'handle' => $groupmembership->side->op->handle,
                        ],
                    ],
                    'group_id' => $groupmembership->group_id,
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListGroupmembershipAsGuest()
    {
        $this->getJson('/groups/'.$this->group->id.'/groupmemberships')
            ->assertSuccessful()
            ->assertJson($this->groupmemberships->map(function (Groupmembership $groupmembership):array {
                return [
                    'id' => $groupmembership->id,
                    'op_id' => $groupmembership->op->id,
                    'op' => [
                        'id'     => $groupmembership->op->id,
                        'handle' => $groupmembership->op->handle,
                    ],
                    'side_id' => $groupmembership->side_id,
                    'side' => [
                        'id' => $groupmembership->side->id,
                        'name' => $groupmembership->side->name,
                        'text'  => $groupmembership->side->text,
                        'op_id' => $groupmembership->side->op->id,
                        'op' => [
                            'id'     => $groupmembership->side->op->id,
                            'handle' => $groupmembership->side->op->handle,
                        ],
                    ],
                    'group_id' => $groupmembership->group_id,
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
