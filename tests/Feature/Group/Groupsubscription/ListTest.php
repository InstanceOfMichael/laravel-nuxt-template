<?php

namespace Tests\Feature\Group\Groupsubscription;

use App\Groupsubscription;
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
    /** @var \App\Groupsubscription[] */
    protected $groupsubscriptions;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->group = factory(Group::class)->create([ 'op_id' => $this->users[3]->id ]);
        $this->groupsubscriptions = collect([
            factory(Groupsubscription::class)->create([
                'user_id' => $this->users[0]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('user', $this->users[0])
            ->setRelation('group', $this->group),
            factory(Groupsubscription::class)->create([
                'user_id' => $this->users[1]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('user', $this->users[1])
            ->setRelation('group', $this->group),
            factory(Groupsubscription::class)->create([
                'user_id' => $this->users[2]->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('user', $this->users[2])
            ->setRelation('group', $this->group),
        ])->sortByDesc('id')->values();
    }

    public function testListGroupsubscriptionAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupsubscriptions')
            ->assertStatus(200)
            ->assertJson($this->groupsubscriptions->map(function (Groupsubscription $groupsubscription):array {
                return [
                    'id' => $groupsubscription->id,
                    'user_id' => $groupsubscription->user_id,
                    'user' => [
                        'id' => $groupsubscription->user->id,
                        'handle' => $groupsubscription->user->handle,
                        'name' => $groupsubscription->user->name,
                    ],
                    'group_id' => $groupsubscription->group_id,
                    'group' => [
                        'id' => $groupsubscription->group->id,
                        'name' => $groupsubscription->group->name,
                        'text' => $groupsubscription->group->text,
                    ],
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListGroupsubscriptionAsGuest()
    {
        $this->getJson('/groups/'.$this->group->id.'/groupsubscriptions')
            ->assertStatus(200)
            ->assertJson($this->groupsubscriptions->map(function (Groupsubscription $groupsubscription):array {
                return [
                    'id' => $groupsubscription->id,
                    'user_id' => $groupsubscription->user_id,
                    'user' => [
                        'id' => $groupsubscription->user->id,
                        'handle' => $groupsubscription->user->handle,
                        'name' => $groupsubscription->user->name,
                    ],
                    'group_id' => $groupsubscription->group_id,
                    'group' => [
                        'id' => $groupsubscription->group->id,
                        'name' => $groupsubscription->group->name,
                        'text' => $groupsubscription->group->text,
                    ],
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
