<?php

namespace Tests\Feature\Definition;

use App\Http\Middleware\Idempotency;
use App\User;
use App\Definition;
use App\Definitiondomain;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Definition */
    protected $definition;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->definition = factory(Definition::class)->make();
        $this->definition->setRelation('op', $this->user);
    }

    protected function getPayload(): array {
        return [
            'name' => $this->definition->name,
            'text'  => $this->definition->text,
        ];
    }

    public function testStoreDefinitionAsUser()
    {
        $definition = $this->definition;
        $this->actingAs($this->user)
            ->postJson('/definitions', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $definition->id,
                'name'  => $definition->name,
                'text'  => $definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreDefinitionAsUserIdempotent()
    {
        $definition = $this->definition;
        $r1 = $this->actingAs($this->user)
            ->postJson('/definitions', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $definition->id,
                'name'  => $definition->name,
                'text'  => $definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r2 = $this->actingAs($this->user)
            ->postJson('/definitions', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $definition->id,
                'name'  => $definition->name,
                'text'  => $definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r3 = $this->actingAs($this->user)
            ->postJson('/definitions', $this->getPayload())
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

    public function testStoreDefinitionIfDefinitionDomainAlreadyExistsAsUser()
    {
        $definition = $this->definition;
        $preexistingDefinition = factory(Definition::class)->create([
            // same text
            'text' => $definition->text.'but-its-technically-different',
        ]);
        $this->actingAs($this->user)
            ->postJson('/definitions', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'id'    => Definition::all()->last()->id,
                'name'  => $definition->name,
                'text'  => $definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreDefinitionAsGuest()
    {
        $this->postJson('/definitions', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreDefinitionEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/definitions', [])
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

    public function testStoreDefinitionEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/definitions', [
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
