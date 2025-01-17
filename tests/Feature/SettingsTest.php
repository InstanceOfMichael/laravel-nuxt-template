<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class SettingsTest extends TestCase
{
    /** @var \App\User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function update_profile_info()
    {
        $this->actingAs($this->user)
            ->patchJson('/settings/profile', [
                'name' => 'Test User',
                'email' => 'test@test.app',
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email']);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Test User',
            'email' => 'test@test.app',
        ]);
    }

    /** @test */
    public function update_password()
    {
        $this->actingAs($this->user)
            ->patchJson('/settings/password', [
                'password' => 'updated',
                'password_confirmation' => 'updated',
            ])
            ->assertStatus(200);

        $this->assertTrue(Hash::check('updated', $this->user->password));
    }
}
