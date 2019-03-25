<?php

namespace Tests\Feature\Group\Groupsubscription;

use App\Groupsubscription;
use App\User;
use App\Side;
use App\Group;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side */
    protected $side;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->side = factory(Side::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->group = factory(Group::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->groupsubscription = factory(Groupsubscription::class)->create([
            'op_id' => $this->users[0]->id,
            'side_id' => $this->side->id,
            'group_id' => $this->group->id,
        ]);
    }

    public function testShowSideAsUser()
    {
        $groupsubscription = $this->groupsubscription;
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id)
            ->assertSuccessful()
            ->assertJson([
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
                'group' => [
                    'id' => $groupsubscription->group->id,
                    'title' => $groupsubscription->group->title,
                    'text'  => $groupsubscription->group->text,
                    'op_id' => $groupsubscription->group->op->id,
                    'op' => [
                        'id'     => $groupsubscription->group->op->id,
                        'handle' => $groupsubscription->group->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowSideAsGuest()
    {
        $groupsubscription = $this->groupsubscription;
        $this->getJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id)
            ->assertSuccessful()
            ->assertJson([
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
                'group' => [
                    'id' => $groupsubscription->group->id,
                    'title' => $groupsubscription->group->title,
                    'text'  => $groupsubscription->group->text,
                    'op_id' => $groupsubscription->group->op->id,
                    'op' => [
                        'id'     => $groupsubscription->group->op->id,
                        'handle' => $groupsubscription->group->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
