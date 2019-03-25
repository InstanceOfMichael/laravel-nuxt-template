<?php

namespace Tests\Feature\Claim;

use App\User;
use App\Claim;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Claim */
    protected $updatedClaim;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->user->id,
        ]);
        $q = factory(Claim::class)->make();
        $this->updatedClaim = factory(Claim::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedClaim->title,
            'text'  => $this->updatedClaim->text,
        ];
    }

    public function testUpdateClaimAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/claims/'.$this->claim->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->updatedClaim->title,
                'text'  => $this->updatedClaim->text,
                'op_id' => $this->claim->op->id,
                'op' => [
                    'id'     => $this->claim->op->id,
                    'handle' => $this->claim->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateClaimPatchWithoutId()
    {
        $this->patchJson('/claims', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateClaimAsGuest()
    {
        $this->patchJson('/claims/'.$this->claim->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateClaimEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/claims/'.$this->claim->id, [])
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->claim->title,
                'text'  => $this->claim->text,
                'op_id' => $this->claim->op->id,
                'op' => [
                    'id'     => $this->claim->op->id,
                    'handle' => $this->claim->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateClaimEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/claims/'.$this->claim->id, [
                'title' => null,
                'text' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text must be a string."],
                    "title" => ["The title must be a string."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
