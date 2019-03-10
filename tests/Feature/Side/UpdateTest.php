<?php

namespace Tests\Feature\Side;

use App\User;
use App\Side;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Side */
    protected $side;
    /** @var \App\Side */
    protected $updatedSide;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->side = factory(Side::class)->create([
            'op_id' => $this->user->id,
        ]);
        $q = factory(Side::class)->make();
        $this->updatedSide = factory(Side::class)->make();
    }

    protected function getPayload(): array {
        return [
            // 'name' => $this->updatedSide->name,
            'text'  => $this->updatedSide->text,
        ];
    }

    public function testUpdateSideAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->side->name,
                'text'  => $this->updatedSide->text,
                'op_id' => $this->side->op->id,
                'op' => [
                    'id'     => $this->side->op->id,
                    'handle' => $this->side->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateSidePatchWithoutId()
    {
        $this->patchJson('/sides', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateSideAsGuest()
    {
        $this->patchJson('/sides/'.$this->side->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateSideEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, [])
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->side->name,
                'text'  => $this->side->text,
                'op_id' => $this->side->op->id,
                'op' => [
                    'id'     => $this->side->op->id,
                    'handle' => $this->side->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateSideEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, [
                'name' => null,
                'text' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => [
                        "The name must be a string.",
                        "The name must be at least 1 characters.",
                        "The name must be the same (case insensitive).",
                    ],
                    "text" => ["The text must be a string."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateSideEmptyNameString()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, [
                'name' => '',
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "name" => [
                        "The name must be a string.",
                        "The name must be at least 1 characters.",
                        "The name must be the same (case insensitive).",
                    ],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateSideNameChangedPrefixOneLetter()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, [
                'name' => 'a'.$this->side->name,
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

    public function testUpdateSideNameChangedOnlyUppercase()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, [
                'name' => strtoupper($this->side->name),
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => strtoupper($this->side->name),
                'text'  => $this->side->text,
                'op_id' => $this->side->op->id,
                'op' => [
                    'id'     => $this->side->op->id,
                    'handle' => $this->side->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateSideNameChangedOnlyLowercase()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, [
                'name' => strtolower($this->side->name),
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => strtolower($this->side->name),
                'text'  => $this->side->text,
                'op_id' => $this->side->op->id,
                'op' => [
                    'id'     => $this->side->op->id,
                    'handle' => $this->side->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateSideNameNoChanged()
    {
        $this->actingAs($this->user)
            ->patchJson('/sides/'.$this->side->id, [
                'name' => $this->side->name,
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->side->name,
                'text'  => $this->side->text,
                'op_id' => $this->side->op->id,
                'op' => [
                    'id'     => $this->side->op->id,
                    'handle' => $this->side->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
