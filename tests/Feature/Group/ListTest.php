<?php

namespace Tests\Feature\Group;

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

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->groups = collect([
            factory(Group::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Group::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Group::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
    }

    public function testListGroupAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/groups')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->groups->reverse()->map(function (Group $q):array {
                    return [
                        'id'    => $q->id,
                        'name'  => $q->name,
                        'text'  => $q->text,
                        'op_id' => $q->op_id,
                        'op' => [
                            'id'     => $q->op->id,
                            'handle' => $q->op->handle,
                        ],
                    ];
                })->values()->all(),
                'total' => $this->groups->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListGroupAsGuest()
    {
        $this->getJson('/groups')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->groups->reverse()->map(function (Group $q):array {
                    return [
                        'id'    => $q->id,
                        'name'  => $q->name,
                        'text'  => $q->text,
                        'op_id' => $q->op_id,
                        'op' => [
                            'id'     => $q->op->id,
                            'handle' => $q->op->handle,
                        ],
                    ];
                })->values()->all(),
                'total' => $this->groups->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
