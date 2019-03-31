<?php

namespace Tests\Feature\Claim;

use App\User;
use App\Claim;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Claim */
    protected $claim;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->user->id,
        ]);
    }

    public function testShowClaimAsUser()
    {
        $this->actingAs($this->user)
            ->getJson('/claims/'.$this->claim->id)
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->claim->title,
                'text'  => $this->claim->text,
                'op_id' => $this->claim->op_id,
                'op' => [
                    'id'     => $this->claim->op->id,
                    'handle' => $this->claim->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testShowClaimAsGuest()
    {
        $this->getJson('/claims/'.$this->claim->id)
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->claim->title,
                'text'  => $this->claim->text,
                'op_id' => $this->claim->op_id,
                'op' => [
                    'id'     => $this->claim->op->id,
                    'handle' => $this->claim->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
