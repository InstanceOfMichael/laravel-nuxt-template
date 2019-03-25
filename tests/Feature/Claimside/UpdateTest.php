<?php

namespace Tests\Feature\Claimside;

use App\Claimside;
use App\User;
use App\Side;
use App\Claim;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side */
    protected $side;
    /** @var \App\Claimside */
    protected $claimside;
    /** @var \App\Claimside */
    protected $updatedClaimside;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->side = factory(Side::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->claimside = factory(Claimside::class)->create([
            'op_id' => $this->users[0]->id,
            'side_id' => $this->side->id,
            'claim_id' => $this->claim->id,
        ]);
        $this->updatedClaimside = factory(Claimside::class)->make([
            'op_id' => $this->users[0]->id,
        ]);
    }

    protected function getPayload(): array {
        return [
            // 'title' => $this->updatedClaimside->title,
            // 'text'  => $this->updatedClaimside->text,
        ];
    }

    public function testUpdateAllowedquestionsideAsUser()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/claims/'.$this->claim->id.'/claimsides/'.$this->claimside->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->claimside->id,
                'op_id' => $this->claimside->op->id,
                'side_id' => $this->claimside->side_id,
                'claim_id' => $this->claimside->claim->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateAllowedquestionsidePatchWithoutId()
    {
        $this->patchJson('/claims/'.$this->claim->id.'/claimsides', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateAllowedquestionsideAsGuest()
    {
        $this->patchJson('/claims/'.$this->claim->id.'/claimsides/'.$this->claimside->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateAllowedquestionsideEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claims/'.$this->claim->id.'/claimsides/'.$this->claimside->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->claimside->id,
                'op_id' => $this->claimside->op->id,
                'side_id' => $this->claimside->side_id,
                'claim_id' => $this->claimside->claim->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateAllowedquestionsideEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/claims/'.$this->claim->id.'/claimsides/'.$this->claimside->id, [
                'side_id' => null,
                'claim_id' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["The side id field is required."],
                    "claim_id" => ["The claim id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
