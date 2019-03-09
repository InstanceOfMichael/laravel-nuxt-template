<?php

namespace Tests\Feature\Claimside;

use App\Claimside;
use App\User;
use App\Side;
use App\Claim;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side */
    protected $side;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Claimside */
    protected $claimside;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claim->setRelation('op', $this->users[1]);
        $this->sides = factory(Side::class, 2)->create([
            'op_id' => $this->users[2]->id,
        ]);

        foreach($this->sides as $side) {
            $side->setRelation('op', $this->users[2]);
        }
        $this->claimsides = $this->sides->map(function (Side $side) {
            return $this->claimsides = factory(Claimside::class)->make([
                'op_id' => $this->users[0]->id,
                'side_id' => $side->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[0])
            ->setRelation('claim', $this->claim)
            ->setRelation('side', $side);
        });
    }

    protected function getPayload(): array {
        return [
            'side_id' => $this->claimsides[0]->side_id,
        ];
    }

    public function testStoreAllowedquestionsideAsUser()
    {
        $claimside = $this->claimsides[0];
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimsides', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $claimside->id,
                'op_id' => $claimside->op->id,
                'side_id' => $claimside->side_id,
                'claim_id' => $claimside->claim_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    public function testStoreAllowedquestionsideAsGuest()
    {
        $this->postJson('/claims/'.$this->claim->id.'/claimsides', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreAllowedquestionsideEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimsides', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["The side id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreAllowedquestionsideEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimsides', [
                "side_id" => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["The side id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreAllowedquestionsideEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/claimsides', [
                "side_id" => 0,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["The selected side id is invalid."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
