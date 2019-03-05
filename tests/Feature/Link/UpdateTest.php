<?php

namespace Tests\Feature\Link;

use App\User;
use App\Link;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

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
            'title' => $this->updatedLink->title,
            'url'  => $this->updatedLink->url,
        ];
    }

    public function testUpdateLinkAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/links/'.$this->link->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->updatedLink->title,
                'url'  => $this->updatedLink->url,
                'op_id' => $this->link->op->id,
                'op' => [
                    'id'     => $this->link->op->id,
                    'handle' => $this->link->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateLinkPatchWithoutId()
    {
        $this->patchJson('/links', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateLinkAsGuest()
    {
        $this->patchJson('/links/'.$this->link->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateLinkEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/links/'.$this->link->id, [])
            ->assertStatus(200)
            ->assertJson([
                'title' => $this->link->title,
                'url'  => $this->link->url,
                'op_id' => $this->link->op->id,
                'op' => [
                    'id'     => $this->link->op->id,
                    'handle' => $this->link->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateLinkEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/links/'.$this->link->id, [
                'title' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "title" => ["The title must be a string."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
