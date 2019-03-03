<?php

namespace Tests\Feature\Claim;

use App\User;
use App\Claim;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim[] */
    protected $claims;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->claims = collect([
            factory(Claim::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
    }

    public function testListClaimAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/claims')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->claims->map(function (Claim $c):array {
                    return [
                        'id'    => $c->id,
                        'title'  => $c->title,
                        'text'  => $c->text,
                        'op_id' => $c->op_id,
                        'op' => [
                            'id'     => $c->op->id,
                            'handle' => $c->op->handle,
                        ],
                    ];
                })->all(),
                'total' => $this->claims->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListClaimAsGuest()
    {
        $this->getJson('/claims')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->claims->map(function (Claim $c):array {
                    return [
                        'id'    => $c->id,
                        'title'  => $c->title,
                        'text'  => $c->text,
                        'op_id' => $c->op_id,
                        'op' => [
                            'id'     => $c->op->id,
                            'handle' => $c->op->handle,
                        ],
                    ];
                })->all(),
                'total' => $this->claims->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
