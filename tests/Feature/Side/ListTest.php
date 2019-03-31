<?php

namespace Tests\Feature\Side;

use App\User;
use App\Side;
use Tests\TestCase;

/**
 * @group list
 */
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
            factory(Side::class)->create(),
            factory(Side::class)->create(),
            factory(Side::class)->create(),
        ])->sortByDesc('id')->values();
    }

    public function testListSideAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/sides')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->sides->map(function (Side $side):array {
                    return [
                        'id'    => $side->id,
                        'name'  => $side->name,
                        'text'  => $side->text,
                    ];
                })->all(),
                'total' => $this->sides->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListSideAsGuest()
    {
        $this->getJson('/sides')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->sides->map(function (Side $side):array {
                    return [
                        'id'    => $side->id,
                        'name'  => $side->name,
                        'text'  => $side->text,
                    ];
                })->all(),
                'total' => $this->sides->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
