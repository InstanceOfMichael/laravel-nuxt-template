<?php

namespace Tests\Feature\Side;

use App\Http\Middleware\Idempotency;
use App\User;
use App\Side;
use App\Sidedomain;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Side */
    protected $side;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->side = factory(Side::class)->make([
            'op_id' => $this->user->id,
        ]);
        $this->side->setRelation('op', $this->user);
    }

    protected function getPayload(): array {
        return [
            'name' => $this->side->name,
            'text'  => $this->side->text,
        ];
    }

    public function testStoreSideAsUser()
    {
        $side = $this->side;
        $this->actingAs($this->user)
            ->postJson('/sides', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $side->id,
                'name'  => $side->name,
                'text'  => $side->text,
                'op_id' => $side->op_id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreSideAsUserIdempotent()
    {
        $side = $this->side;
        $r1 = $this->actingAs($this->user)
            ->postJson('/sides', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $side->id,
                'name'  => $side->name,
                'text'  => $side->text,
                'op_id' => $side->op_id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r2 = $this->actingAs($this->user)
            ->postJson('/sides', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $side->id,
                'name'  => $side->name,
                'text'  => $side->text,
                'op_id' => $side->op_id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r3 = $this->actingAs($this->user)
            ->postJson('/sides', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => ["The name must be unique (case insensitive)."],
                    // "text" => ["The text field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreSideIfSideDomainAlreadyExistsAsUser()
    {
        $side = $this->side;
        $preexistingSide = factory(Side::class)->create([
            // same domain
            'text' => $side->text.'but-its-technically-different',
            'op_id' => factory(User::class)->create()->id,
        ]);
        $this->actingAs($this->user)
            ->postJson('/sides', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'id'    => Side::all()->last()->id,
                'name'  => $side->name,
                'text'  => $side->text,
                'op_id' => $side->op_id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreSideAsGuest()
    {
        $this->postJson('/sides', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreSideEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/sides', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => ["The name field is required."],
                    // "text" => ["The text field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreSideEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/sides', [
                'text' => null,
                'name' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => ["The name field is required."],
                    "text" => ["The text must be a string."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
