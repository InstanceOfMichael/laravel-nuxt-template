<?php

namespace Tests\Feature\Claimrelation;

use App\Claimrelation;
use App\User;
use App\Claim;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $replyclaim;
    /** @var \App\Claim */
    protected $parentclaim;
    /** @var \App\Claimrelation */
    protected $claimrelation;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->replyclaim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->parentclaim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claimrelation = factory(Claimrelation::class)->create([
            'op_id' => $this->users[0]->id,
            'rc_id' => $this->replyclaim->id,
            'pc_id' => $this->parentclaim->id,
        ])
        ->setRelation('replyclaim', $this->replyclaim)
        ->setRelation('parentclaim', $this->parentclaim);
    }

    public function testShowClaimrelationAsUser()
    {
        $claimrelation = $this->claimrelation;
        $this->actingAs($this->users[0])
            ->getJson('/claimrelations/'.$this->claimrelation->id)
            ->assertSuccessful()
            ->assertJson([
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
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowClaimrelationAsGuest()
    {
        $claimrelation = $this->claimrelation;
        $this->getJson('/claimrelations/'.$this->claimrelation->id)
            ->assertSuccessful()
            ->assertJson([
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
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
