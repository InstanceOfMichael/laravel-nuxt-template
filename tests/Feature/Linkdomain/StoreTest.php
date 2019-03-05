<?php

namespace Tests\Feature\Linkdomain;

use App\User;
use App\Link;
use App\Linkdomain;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class StoreTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Link */
    protected $link;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    protected function getPayload(): array {
        return [
            'text' => $this->faker->paragraph,
            'domain'  => $this->faker->domainName,
        ];
    }

    public function testStoreLinkAsUser()
    {
        $link = $this->link;
        $this->actingAs($this->user)
            ->postJson('/linkdomains', $this->getPayload())
            ->assertStatus(403)
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreLinkAsGuest()
    {
        $this->postJson('/linkdomains', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreLinkEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/linkdomains', [])
            ->assertStatus(403)
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreLinkEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/linkdomains', [
                'url' => null,
                'title' => null,
            ])
            ->assertStatus(403)
            ->assertDontExposeUserEmails($this->user->email);
    }
}
