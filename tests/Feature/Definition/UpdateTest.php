<?php

namespace Tests\Feature\Definition;

use App\User;
use App\Definition;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Definition */
    protected $definition;
    /** @var \App\Definition */
    protected $updatedDefinition;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->definition = factory(Definition::class)->create();
        $q = factory(Definition::class)->make();
        $this->updatedDefinition = factory(Definition::class)->make();
    }

    protected function getPayload(): array {
        return [
            // 'name' => $this->updatedDefinition->name,
            'text'  => $this->updatedDefinition->text,
        ];
    }

    public function testUpdateDefinitionAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->definition->name,
                'text'  => $this->updatedDefinition->text,
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateDefinitionPatchWithoutId()
    {
        $this->patchJson('/definitions', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateDefinitionAsGuest()
    {
        $this->patchJson('/definitions/'.$this->definition->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateDefinitionEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, [])
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->definition->name,
                'text'  => $this->definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateDefinitionEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, [
                'name' => null,
                'text' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => [
                        "The name must be a string.",
                        "The name must be at least 3 characters.",
                        "The name must be the same (case insensitive).",
                    ],
                    "text" => ["The text must be a string."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateDefinitionEmptyNameString()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, [
                'name' => '',
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => [
                        "The name must be a string.",
                        "The name must be at least 3 characters.",
                        "The name must be the same (case insensitive).",
                    ],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateDefinitionNameChangedPrefixOneLetter()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, [
                'name' => 'a'.$this->definition->name,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => [
                        "The name must be the same (case insensitive).",
                    ],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateDefinitionNameChangedOnlyUppercase()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, [
                'name' => strtoupper($this->definition->name),
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => strtoupper($this->definition->name),
                'text'  => $this->definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateDefinitionNameChangedOnlyLowercase()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, [
                'name' => strtolower($this->definition->name),
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => strtolower($this->definition->name),
                'text'  => $this->definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateDefinitionNameNoChanged()
    {
        $this->actingAs($this->user)
            ->patchJson('/definitions/'.$this->definition->id, [
                'name' => $this->definition->name,
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->definition->name,
                'text'  => $this->definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
