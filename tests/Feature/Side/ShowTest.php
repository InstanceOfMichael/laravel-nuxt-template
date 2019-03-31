<?php

namespace Tests\Feature\Side;

use App\User;
use App\Side;
use Tests\TestCase;

/**
 * @group show
 */
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
        $this->side = factory(Side::class)->create();
    }

    public function testShowSideAsUser()
    {
        $side = $this->side;
        $this->actingAs($this->user)
            ->getJson('/sides/'.$this->side->id)
            ->assertStatus(200)
            ->assertJson([
                'id'    => $side->id,
                'name'  => $side->name,
                'text'  => $side->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testShowSideAsGuest()
    {
        $side = $this->side;
        $this->getJson('/sides/'.$this->side->id)
            ->assertStatus(200)
            ->assertJson([
                'id'    => $side->id,
                'name'  => $side->name,
                'text'  => $side->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
