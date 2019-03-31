<?php

namespace Tests\Feature\Group;

use App\Http\Middleware\Idempotency;
use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Group */
    protected $group;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->group = factory(Group::class)->make([
            // 'op_id' => $this->user->id,
        ]);
        $this->group->setRelation('op', $this->user);
    }

    protected function getPayload(): array {
        return [
            'name' => $this->group->name,
            'text'  => $this->group->text,
        ];
    }

    public function testStoreGroupAsUser()
    {
        $this->actingAs($this->user)
            ->postJson('/groups', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'name' => $this->group->name,
                'text'  => $this->group->text,
                'op_id' => $this->group->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }


    /**
     * @group idempotency
     */
    public function testStoreGroupAsUserIdempotent()
    {
        $r1 = $this->actingAs($this->user)
            ->postJson('/groups', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'name' => $this->group->name,
                'text'  => $this->group->text,
                'op_id' => $this->group->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r2 = $this->actingAs($this->user)
            ->postJson('/groups', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'name' => $this->group->name,
                'text'  => $this->group->text,
                'op_id' => $this->group->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r3 = $this->actingAs($this->user)
            ->postJson('/groups', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'name' => $this->group->name,
                'text'  => $this->group->text,
                'op_id' => $this->group->op->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
    }

    public function testStoreGroupAsGuest()
    {
        $this->postJson('/groups', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreGroupEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/groups', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    // "text" => ["The text field is required."],
                    "name" => ["The name field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreGroupEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/groups', [
                'text' => null,
                'name' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text must be a string."],
                    "name" => ["The name field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
