<?php

namespace Tests\Feature\Claim;

use App\User;
use App\Claim;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class StoreTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Claim */
    protected $claim;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->claim = factory(Claim::class)->make([
            // 'op_id' => $this->user->id,
        ]);
        $this->claim->setRelation('op', $this->user);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->claim->title,
            'text'  => $this->claim->text,
        ];
    }

    public function testStoreClaimAsUser()
    {
        $this->actingAs($this->user)
            ->postJson('/claims', $this->getPayload())
            ->assertStatus(201)
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

    public function testStoreClaimAsGuest()
    {
        $this->postJson('/claims', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreClaimEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/claims', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "title" => ["The title field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreClaimEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/claims', [
                "text" => null,
                "title" => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text must be a string."],
                    "title" => ["The title field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
