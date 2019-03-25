<?php

namespace Tests\Feature\Claimrelation;

use App\Claimrelation;
use App\User;
use App\Claim;
use App\Http\Middleware\Idempotency;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $parentclaim;
    /** @var \App\Claim */
    protected $replyclaim;
    /** @var \App\Claimrelation */
    protected $claimrelation;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->parentclaim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->parentclaim->setRelation('op', $this->users[1]);
        $this->replyclaim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->replyclaim->setRelation('op', $this->users[2]);
        $this->claimrelation = factory(Claimrelation::class)->make([
            'op_id' => $this->users[0]->id,
            'rc_id' => $this->replyclaim->id,
            'pc_id' => $this->parentclaim->id,
        ]);
        $this->claimrelation->setRelation('op', $this->users[0]);
        $this->claimrelation->setRelation('parentclaim', $this->parentclaim);
        $this->claimrelation->setRelation('replyclaim', $this->replyclaim);
    }

    protected function getPayload(): array {
        return [
            'type' => $this->claimrelation->type,
            'rc_id' => $this->claimrelation->rc_id,
            'pc_id'  => $this->claimrelation->pc_id,
        ];
    }

    public function testStoreClaimrelationAsUser()
    {
        $claimrelation = $this->claimrelation;
        $this->actingAs($this->users[0])
            ->postJson('/claimrelations', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $claimrelation->id,
                'op_id' => $claimrelation->op->id,
                'rc_id' => $claimrelation->rc_id,
                'pc_id' => $claimrelation->pc_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreClaimrelationAsUserIdempotent()
    {
        $claimrelation = $this->claimrelation;
        $r1 = $this->actingAs($this->users[0])
            ->postJson('/claimrelations', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $claimrelation->id,
                'op_id' => $claimrelation->op->id,
                'rc_id' => $claimrelation->rc_id,
                'pc_id' => $claimrelation->pc_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r2 = $this->actingAs($this->users[0])
            ->postJson('/claimrelations', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $claimrelation->id,
                'op_id' => $claimrelation->op->id,
                'rc_id' => $claimrelation->rc_id,
                'pc_id' => $claimrelation->pc_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/claimrelations', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "rc_id" => ["These claims are already associated."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreClaimrelationAsGuest()
    {
        $this->postJson('/claimrelations', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreClaimrelationEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claimrelations', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "rc_id" => ["The rc id field is required."],
                    "pc_id" => ["The pc id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreClaimrelationEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claimrelations', [
                "rc_id" => null,
                "pc_id" => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "rc_id" => ["The rc id field is required."],
                    "pc_id" => ["The pc id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreClaimrelationEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claimrelations', [
                "rc_id" => 0,
                "pc_id" => 0,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "rc_id" => ["The selected rc id is invalid."],
                    "pc_id" => ["The selected pc id is invalid."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
