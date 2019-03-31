<?php

namespace Tests\Feature\Topic;

use App\User;
use App\Topic;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Topic */
    protected $topic;
    /** @var \App\Topic */
    protected $updatedTopic;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->topic = factory(Topic::class)->create();
        $q = factory(Topic::class)->make();
        $this->updatedTopic = factory(Topic::class)->make();
    }

    protected function getPayload(): array {
        return [
            // 'name' => $this->updatedTopic->name,
            'text'  => $this->updatedTopic->text,
        ];
    }

    public function testUpdateTopicAsUser()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->topic->name,
                'text'  => $this->updatedTopic->text,
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateTopicPatchWithoutId()
    {
        $this->patchJson('/topics', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateTopicAsGuest()
    {
        $this->patchJson('/topics/'.$this->topic->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateTopicEmptyPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, [])
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->topic->name,
                'text'  => $this->topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateTopicEmptyNullPayload()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, [
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

    public function testUpdateTopicEmptyNameString()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, [
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

    public function testUpdateTopicNameChangedPrefixOneLetter()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, [
                'name' => 'a'.$this->topic->name,
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

    public function testUpdateTopicNameChangedOnlyUppercase()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, [
                'name' => strtoupper($this->topic->name),
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => strtoupper($this->topic->name),
                'text'  => $this->topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateTopicNameChangedOnlyLowercase()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, [
                'name' => strtolower($this->topic->name),
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => strtolower($this->topic->name),
                'text'  => $this->topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testUpdateTopicNameNoChanged()
    {
        $this->actingAs($this->user)
            ->patchJson('/topics/'.$this->topic->id, [
                'name' => $this->topic->name,
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->topic->name,
                'text'  => $this->topic->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
