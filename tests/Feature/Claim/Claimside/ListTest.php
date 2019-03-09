<?php

namespace Tests\Feature\Claim\Claimside;

use App\Claimside;
use App\User;
use App\Side;
use App\Claim;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side[] */
    protected $claims;
    /** @var \App\Claim[] */
    protected $sides;
    /** @var \App\Claimside[] */
    protected $claimsides;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->sides = collect([
            factory(Side::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Side::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
        $this->claim = factory(Claim::class)->create([ 'op_id' => $this->users[3]->id ]);
        $this->claimsides = collect([
            factory(Claimside::class)->create([
                'op_id' => $this->users[6]->id,
                'side_id' => $this->sides[0]->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('side', $this->sides[0])
            ->setRelation('claim', $this->claim),
            factory(Claimside::class)->create([
                'op_id' => $this->users[7]->id,
                'side_id' => $this->sides[1]->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('side', $this->sides[1])
            ->setRelation('claim', $this->claim),
            factory(Claimside::class)->create([
                'op_id' => $this->users[8]->id,
                'side_id' => $this->sides[2]->id,
                'claim_id' => $this->claim->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('side', $this->sides[2])
            ->setRelation('claim', $this->claim),
        ])->sortByDesc('id')->values();
    }

    public function testListAllowedquestionsideAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/claims/'.$this->claim->id.'/claimsides')
            ->assertSuccessful()
            ->assertJson($this->claimsides->map(function (Claimside $claimside):array {
                return [
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
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListAllowedquestionsideAsGuest()
    {
        $this->getJson('/claims/'.$this->claim->id.'/claimsides')
            ->assertSuccessful()
            ->assertJson($this->claimsides->map(function (Claimside $claimside):array {
                return [
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
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
