<?php

namespace Tests\Feature\Claimrelation;

use App\Claimrelation;
use App\User;
use App\Claim;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Claimrelation */
    protected $claimrelation;
    /** @var \App\Claimrelation */
    protected $updatedClaimrelation;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->question = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->claimrelation = factory(Claimrelation::class)->create([
            'op_id' => $this->users[0]->id,
            'rc_id' => $this->claim->id,
            'pc_id' => $this->question->id,
        ]);
        $this->updatedClaimrelation = factory(Claimrelation::class)->make([
            'op_id' => $this->users[0]->id,
        ]);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedClaimrelation->title,
            'text'  => $this->updatedClaimrelation->text,
        ];
    }

    public function testUpdateClaimrelationAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claimrelations/'.$this->claimrelation->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->claimrelation->id,
                'op_id' => $this->claimrelation->op->id,
                'rc_id' => $this->claimrelation->rc_id,
                'pc_id' => $this->claimrelation->pc_id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateClaimrelationPatchWithoutId()
    {
        $this->patchJson('/claimrelations', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateClaimrelationAsGuest()
    {
        $this->patchJson('/claimrelations/'.$this->claimrelation->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateClaimrelationEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claimrelations/'.$this->claimrelation->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->claimrelation->id,
                'op_id' => $this->claimrelation->op->id,
                'rc_id' => $this->claimrelation->rc_id,
                'pc_id' => $this->claimrelation->pc_id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateClaimrelationEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/claimrelations/'.$this->claimrelation->id, [
                'rc_id' => null,
                'pc_id' => null,
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
}
