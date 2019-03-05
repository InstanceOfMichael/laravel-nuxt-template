<?php

namespace Tests\Feature\Claimrelation;

use App\Claimrelation;
use App\User;
use App\Claim;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim[] */
    protected $replyclaim;
    /** @var \App\Claim[] */
    protected $parentclaims;
    /** @var \App\Claimrelation[] */
    protected $claimrelations;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->replyclaims = collect([
            factory(Claim::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
        $this->parentclaims = collect([
            factory(Claim::class)->create([ 'op_id' => $this->users[3]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[4]->id ]),
            factory(Claim::class)->create([ 'op_id' => $this->users[5]->id ]),
        ]);
        $this->claimrelations = collect([
            factory(Claimrelation::class)->create([
                'op_id' => $this->users[6]->id,
                'rc_id' => $this->replyclaims[0]->id,
                'pc_id' => $this->parentclaims[0]->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('replyclaim', $this->replyclaims[0])
            ->setRelation('parentclaim', $this->parentclaims[0]),
            factory(Claimrelation::class)->create([
                'op_id' => $this->users[7]->id,
                'rc_id' => $this->replyclaims[1]->id,
                'pc_id' => $this->parentclaims[1]->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('replyclaim', $this->replyclaims[1])
            ->setRelation('parentclaim', $this->parentclaims[1]),
            factory(Claimrelation::class)->create([
                'op_id' => $this->users[8]->id,
                'rc_id' => $this->replyclaims[2]->id,
                'pc_id' => $this->parentclaims[2]->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('replyclaim', $this->replyclaims[2])
            ->setRelation('parentclaim', $this->parentclaims[2]),
        ])->sortByDesc('id')->values();
    }

    public function testListClaimrelationAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/claimrelations')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->claimrelations->map(function (Claimrelation $claimrelation):array {
                    return [
                        'id' => $claimrelation->id,
                        'op_id' => $claimrelation->op->id,
                        'op' => [
                            'id'     => $claimrelation->op->id,
                            'handle' => $claimrelation->op->handle,
                        ],
                        'rc_id' => $claimrelation->rc_id,
                        'replyclaim' => [
                            'id' => $claimrelation->replyclaim->id,
                            'title' => $claimrelation->replyclaim->title,
                            'text'  => $claimrelation->replyclaim->text,
                            'op_id' => $claimrelation->replyclaim->op->id,
                            'op' => [
                                'id'     => $claimrelation->replyclaim->op->id,
                                'handle' => $claimrelation->replyclaim->op->handle,
                            ],
                        ],
                        'pc_id' => $claimrelation->pc_id,
                        'parentclaim' => [
                            'id' => $claimrelation->parentclaim->id,
                            'title' => $claimrelation->parentclaim->title,
                            'text'  => $claimrelation->parentclaim->text,
                            'op_id' => $claimrelation->parentclaim->op->id,
                            'op' => [
                                'id'     => $claimrelation->parentclaim->op->id,
                                'handle' => $claimrelation->parentclaim->op->handle,
                            ],
                        ],
                    ];
                })->all(),
                'total' => $this->claimrelations->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListClaimrelationAsGuest()
    {
        $this->getJson('/claimrelations')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->claimrelations->map(function (Claimrelation $claimrelation):array {
                    return [
                        'id' => $claimrelation->id,
                        'op_id' => $claimrelation->op->id,
                        'op' => [
                            'id'     => $claimrelation->op->id,
                            'handle' => $claimrelation->op->handle,
                        ],
                        'rc_id' => $claimrelation->rc_id,
                        'replyclaim' => [
                            'id' => $claimrelation->replyclaim->id,
                            'title' => $claimrelation->replyclaim->title,
                            'text'  => $claimrelation->replyclaim->text,
                            'op_id' => $claimrelation->replyclaim->op->id,
                            'op' => [
                                'id'     => $claimrelation->replyclaim->op->id,
                                'handle' => $claimrelation->replyclaim->op->handle,
                            ],
                        ],
                        'pc_id' => $claimrelation->pc_id,
                        'parentclaim' => [
                            'id' => $claimrelation->parentclaim->id,
                            'title' => $claimrelation->parentclaim->title,
                            'text'  => $claimrelation->parentclaim->text,
                            'op_id' => $claimrelation->parentclaim->op->id,
                            'op' => [
                                'id'     => $claimrelation->parentclaim->op->id,
                                'handle' => $claimrelation->parentclaim->op->handle,
                            ],
                        ],
                    ];
                })->all(),
                'total' => $this->claimrelations->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
