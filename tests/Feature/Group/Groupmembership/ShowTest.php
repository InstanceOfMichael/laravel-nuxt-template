<?php

namespace Tests\Feature\Group\Groupmembership;

use App\Groupmembership;
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
        $this->groupmembership = factory(Groupmembership::class)->create([
            'op_id' => $this->users[0]->id,
            'side_id' => $this->side->id,
            'group_id' => $this->group->id,
        ]);
    }

    public function testShowSideAsUser()
    {
        $groupmembership = $this->groupmembership;
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id)
            ->assertSuccessful()
            ->assertJson([
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
                'group' => [
                    'id' => $groupmembership->group->id,
                    'title' => $groupmembership->group->title,
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

    public function testShowSideAsGuest()
    {
        $groupmembership = $this->groupmembership;
        $this->getJson('/groups/'.$this->group->id.'/groupmemberships/'.$this->groupmembership->id)
            ->assertSuccessful()
            ->assertJson([
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
                'group' => [
                    'id' => $groupmembership->group->id,
                    'title' => $groupmembership->group->title,
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
