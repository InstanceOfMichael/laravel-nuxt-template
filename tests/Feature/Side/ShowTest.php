<?php

namespace Tests\Feature\Side;

use App\User;
use App\Side;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ShowTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Side */
    protected $side;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->side = factory(Side::class)->create([
            'op_id' => $this->user->id,
        ]);
    }

    public function testShowSideAsUser()
    {
        $side = $this->side;
        $this->actingAs($this->user)
            ->getJson('/sides/'.$this->side->id)
            ->assertSuccessful()
            ->assertJson([
                'id'    => $side->id,
                'name'  => $side->name,
                'text'  => $side->text,
                'op_id' => $side->op_id,
                'op' => [
                    'id'     => $side->op->id,
                    'handle' => $side->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testShowSideAsGuest()
    {
        $side = $this->side;
        $this->getJson('/sides/'.$this->side->id)
            ->assertSuccessful()
            ->assertJson([
                'id'    => $side->id,
                'name'  => $side->name,
                'text'  => $side->text,
                'op_id' => $side->op_id,
                'op' => [
                    'id'     => $side->op->id,
                    'handle' => $side->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
