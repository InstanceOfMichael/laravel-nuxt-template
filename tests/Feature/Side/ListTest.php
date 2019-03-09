<?php

namespace Tests\Feature\Side;

use App\User;
use App\Side;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side[] */
    protected $sides;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->sides = collect([
            factory(Side::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[2]->id ]),
        ])->sortByDesc('id')->values();
    }

    public function testListSideAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/sides')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->sides->map(function (Side $side):array {
                    return [
                        'id'    => $side->id,
                        'name'  => $side->name,
                        'text'  => $side->text,
                        'op_id' => $side->op_id,
                        'op' => [
                            'id'     => $side->op->id,
                            'handle' => $side->op->handle,
                        ],
                    ];
                })->all(),
                'total' => $this->sides->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListSideAsGuest()
    {
        $this->getJson('/sides')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->sides->map(function (Side $side):array {
                    return [
                        'id'    => $side->id,
                        'name'  => $side->name,
                        'text'  => $side->text,
                        'op_id' => $side->op_id,
                        'op' => [
                            'id'     => $side->op->id,
                            'handle' => $side->op->handle,
                        ],
                    ];
                })->all(),
                'total' => $this->sides->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
