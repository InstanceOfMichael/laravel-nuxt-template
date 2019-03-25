<?php

namespace Tests\Feature\Group\Groupsubscription;

use App\Groupsubscription;
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
    /** @var \App\Groupsubscription[] */
    protected $groupsubscriptions;

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
        $this->groupsubscriptions = collect([
            factory(Groupsubscription::class)->create([
                'op_id' => $this->users[6]->id,
                'side_id' => $this->sides[0]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('side', $this->sides[0])
            ->setRelation('group', $this->group),
            factory(Groupsubscription::class)->create([
                'op_id' => $this->users[7]->id,
                'side_id' => $this->sides[1]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('side', $this->sides[1])
            ->setRelation('group', $this->group),
            factory(Groupsubscription::class)->create([
                'op_id' => $this->users[8]->id,
                'side_id' => $this->sides[2]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('side', $this->sides[2])
            ->setRelation('group', $this->group),
        ])->sortByDesc('id')->values();
    }

    public function testListGroupsubscriptionAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupsubscriptions')
            ->assertSuccessful()
            ->assertJson($this->groupsubscriptions->map(function (Groupsubscription $groupsubscription):array {
                return [
                    'id' => $groupsubscription->id,
                    'op_id' => $groupsubscription->op->id,
                    'op' => [
                        'id'     => $groupsubscription->op->id,
                        'handle' => $groupsubscription->op->handle,
                    ],
                    'side_id' => $groupsubscription->side_id,
                    'side' => [
                        'id' => $groupsubscription->side->id,
                        'name' => $groupsubscription->side->name,
                        'text'  => $groupsubscription->side->text,
                        'op_id' => $groupsubscription->side->op->id,
                        'op' => [
                            'id'     => $groupsubscription->side->op->id,
                            'handle' => $groupsubscription->side->op->handle,
                        ],
                    ],
                    'group_id' => $groupsubscription->group_id,
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListGroupsubscriptionAsGuest()
    {
        $this->getJson('/groups/'.$this->group->id.'/groupsubscriptions')
            ->assertSuccessful()
            ->assertJson($this->groupsubscriptions->map(function (Groupsubscription $groupsubscription):array {
                return [
                    'id' => $groupsubscription->id,
                    'op_id' => $groupsubscription->op->id,
                    'op' => [
                        'id'     => $groupsubscription->op->id,
                        'handle' => $groupsubscription->op->handle,
                    ],
                    'side_id' => $groupsubscription->side_id,
                    'side' => [
                        'id' => $groupsubscription->side->id,
                        'name' => $groupsubscription->side->name,
                        'text'  => $groupsubscription->side->text,
                        'op_id' => $groupsubscription->side->op->id,
                        'op' => [
                            'id'     => $groupsubscription->side->op->id,
                            'handle' => $groupsubscription->side->op->handle,
                        ],
                    ],
                    'group_id' => $groupsubscription->group_id,
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
