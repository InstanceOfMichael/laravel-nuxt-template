<?php

namespace Tests\Feature\Link;

use App\User;
use App\Http\Middleware\Idempotency;
use App\Link;
use App\Linkdomain;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group store
 */
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
        $this->link = factory(Link::class)->make([
            'op_id' => $this->user->id,
        ]);
        $this->link->setRelation('op', $this->user);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->link->title,
            'url'  => $this->link->url,
        ];
    }

    public function testStoreLinkAsUser()
    {
        $link = $this->link;
        $this->actingAs($this->user)
            ->postJson('/links', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $link->id,
                'title'  => $link->title,
                'url'  => $link->url,
                'op_id' => $link->op_id,
                'ld_id' => Linkdomain::first()->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreLinkAsUserIdempotent()
    {
        $link = $this->link;
        $r1 = $this->actingAs($this->user)
            ->postJson('/links', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $link->id,
                'title'  => $link->title,
                'url'  => $link->url,
                'op_id' => $link->op_id,
                'ld_id' => Linkdomain::first()->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r2 = $this->actingAs($this->user)
            ->postJson('/links', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $link->id,
                'title'  => $link->title,
                'url'  => $link->url,
                'op_id' => $link->op_id,
                'ld_id' => Linkdomain::first()->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r3 = $this->actingAs($this->user)
            ->postJson('/links', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $link->id,
                'title'  => $link->title,
                'url'  => $link->url,
                'op_id' => $link->op_id,
                'ld_id' => Linkdomain::first()->id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
    }

    public function testStoreLinkIfLinkDomainAlreadyExistsAsUser()
    {
        $link = $this->link;
        $preexistingLink = factory(Link::class)->create([
            // same domain
            'url' => $link->url.'but-its-technically-different',
            'op_id' => factory(User::class)->create()->id,
        ]);
        $this->actingAs($this->user)
            ->postJson('/links', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'id'    => Link::all()->last()->id,
                'title'  => $link->title,
                'url'  => $link->url,
                'op_id' => $link->op_id,
                'ld_id' => $preexistingLink->ld_id,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreLinkAsGuest()
    {
        $this->postJson('/links', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreLinkEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/links', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "title" => ["The title field is required."],
                    "url" => ["The url field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreLinkEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/links', [
                'url' => null,
                'title' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "title" => ["The title field is required."],
                    "url" => ["The url field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
