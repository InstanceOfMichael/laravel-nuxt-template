<?php

namespace Tests\Feature\Claim;

use App\User;
use App\Claim;
use App\Http\Middleware\Idempotency;
use Tests\TestCase;

/**
 * @group store
 */
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
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreClaimAsUserIdempotent()
    {
        $r1 = $this->actingAs($this->user)
            ->postJson('/claims', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'title' => $this->claim->title,
                'text'  => $this->claim->text,
                'op_id' => $this->claim->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r2 = $this->actingAs($this->user)
            ->postJson('/claims', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'title' => $this->claim->title,
                'text'  => $this->claim->text,
                'op_id' => $this->claim->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r3 = $this->actingAs($this->user)
            ->postJson('/claims', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'title' => $this->claim->title,
                'text'  => $this->claim->text,
                'op_id' => $this->claim->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
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
