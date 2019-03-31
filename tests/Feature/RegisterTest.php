<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;

class RegisterTest extends TestCase
{
    /** @test */
    public function can_register()
    {
        $this->postJson('/register', [
            'handle' => factory(User::class)->make()->handle,
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ])
        ->assertStatus(201)
        ->assertJsonStructure(['id', 'name', 'email', 'handle', 'created_at']);
    }
}
