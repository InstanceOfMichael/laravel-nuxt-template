<?php

namespace Tests\Feature\Claimside;

use App\Claimside;
use App\User;
use App\Side;
use App\Claim;
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
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claimside = factory(Claimside::class)->create([
            'op_id' => $this->users[0]->id,
            'side_id' => $this->side->id,
            'claim_id' => $this->claim->id,
        ]);
    }

    public function testShowSideAsUser()
    {
        $claimside = $this->claimside;
        $this->actingAs($this->users[0])
            ->getJson('/claims/'.$this->claim->id.'/claimsides/'.$this->claimside->id)
            ->assertSuccessful()
            ->assertJson([
                'id' => $claimside->id,
                'op_id' => $claimside->op->id,
                'op' => [
                    'id'     => $claimside->op->id,
                    'handle' => $claimside->op->handle,
                ],
                'side_id' => $claimside->side_id,
                'side' => [
                    'id' => $claimside->side->id,
                    'name' => $claimside->side->name,
                    'text'  => $claimside->side->text,
                    'op_id' => $claimside->side->op->id,
                    'op' => [
                        'id'     => $claimside->side->op->id,
                        'handle' => $claimside->side->op->handle,
                    ],
                ],
                'claim_id' => $claimside->claim_id,
                'claim' => [
                    'id' => $claimside->claim->id,
                    'title' => $claimside->claim->title,
                    'text'  => $claimside->claim->text,
                    'op_id' => $claimside->claim->op->id,
                    'op' => [
                        'id'     => $claimside->claim->op->id,
                        'handle' => $claimside->claim->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowSideAsGuest()
    {
        $claimside = $this->claimside;
        $this->getJson('/claims/'.$this->claim->id.'/claimsides/'.$this->claimside->id)
            ->assertSuccessful()
            ->assertJson([
                'id' => $claimside->id,
                'op_id' => $claimside->op->id,
                'op' => [
                    'id'     => $claimside->op->id,
                    'handle' => $claimside->op->handle,
                ],
                'side_id' => $claimside->side_id,
                'side' => [
                    'id' => $claimside->side->id,
                    'name' => $claimside->side->name,
                    'text'  => $claimside->side->text,
                    'op_id' => $claimside->side->op->id,
                    'op' => [
                        'id'     => $claimside->side->op->id,
                        'handle' => $claimside->side->op->handle,
                    ],
                ],
                'claim_id' => $claimside->claim_id,
                'claim' => [
                    'id' => $claimside->claim->id,
                    'title' => $claimside->claim->title,
                    'text'  => $claimside->claim->text,
                    'op_id' => $claimside->claim->op->id,
                    'op' => [
                        'id'     => $claimside->claim->op->id,
                        'handle' => $claimside->claim->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
