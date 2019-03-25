<?php

namespace Tests\Feature\Linkdomain;

use App\User;
use App\Link;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Link */
    protected $link;
    /** @var \App\Link */
    protected $updatedLink;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->link = factory(Link::class)->create([
            'op_id' => $this->user->id,
        ]);
        $q = factory(Link::class)->make();
        $this->updatedLink = factory(Link::class)->make();
    }

    protected function getPayload(): array {
        return [
            'text'  => $this->faker->paragraph,
        ];
    }

    public function testUpdateLinkAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/linkdomains/'.$this->link->linkdomain->id, $this->getPayload())
            ->assertStatus(403)
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateLinkPatchWithoutId()
    {
        $this->patchJson('/linkdomains', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateLinkAsGuest()
    {
        $this->patchJson('/linkdomains/'.$this->link->linkdomain->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateLinkEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/linkdomains/'.$this->link->linkdomain->id, [])
            ->assertStatus(403)
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateLinkEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/linkdomains/'.$this->link->linkdomain->id, [
                'title' => null,
            ])
            ->assertStatus(403)
            ->assertDontExposeUserEmails($this->user->email);
    }
}
