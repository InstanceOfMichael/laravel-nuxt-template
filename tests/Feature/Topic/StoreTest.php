<?php

namespace Tests\Feature\Topic;

use App\Http\Middleware\Idempotency;
use App\User;
use App\Topic;
use App\Topicdomain;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Topic */
    protected $topic;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->topic = factory(Topic::class)->make();
        $this->topic->setRelation('op', $this->user);
    }

    protected function getPayload(): array {
        return [
            'name' => $this->topic->name,
            'text'  => $this->topic->text,
        ];
    }

    public function testStoreTopicAsUser()
    {
        $topic = $this->topic;
        $this->actingAs($this->user)
            ->postJson('/topics', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $topic->id,
                'name'  => $topic->name,
                'text'  => $topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreTopicAsUserIdempotent()
    {
        $topic = $this->topic;
        $r1 = $this->actingAs($this->user)
            ->postJson('/topics', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $topic->id,
                'name'  => $topic->name,
                'text'  => $topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r2 = $this->actingAs($this->user)
            ->postJson('/topics', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id'    => $topic->id,
                'name'  => $topic->name,
                'text'  => $topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
        $r3 = $this->actingAs($this->user)
            ->postJson('/topics', $this->getPayload())
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

    public function testStoreTopicIfTopicDomainAlreadyExistsAsUser()
    {
        $topic = $this->topic;
        $preexistingTopic = factory(Topic::class)->create([
            // same text
            'text' => $topic->text.'but-its-technically-different',
        ]);
        $this->actingAs($this->user)
            ->postJson('/topics', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'id'    => Topic::all()->last()->id,
                'name'  => $topic->name,
                'text'  => $topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testStoreTopicAsGuest()
    {
        $this->postJson('/topics', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreTopicEmptyPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/topics', [])
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

    public function testStoreTopicEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->postJson('/topics', [
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
