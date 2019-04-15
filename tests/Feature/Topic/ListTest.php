<?php

namespace Tests\Feature\Topic;

use App\User;
use App\Topic;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic[] */
    protected $topics;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->topics = collect([
            factory(Topic::class)->create(),
            factory(Topic::class)->create(),
            factory(Topic::class)->create(),
        ])->sortByDesc('id')->values();
    }

    public function testListTopicAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/topics')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->topics->map(function (Topic $topic):array {
                    return [
                        'id'    => $topic->id,
                        'name'  => $topic->name,
                        'text'  => $topic->text,
                    ];
                })->all(),
                'total' => $this->topics->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListTopicAsGuest()
    {
        $this->getJson('/topics')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->topics->map(function (Topic $topic):array {
                    return [
                        'id'    => $topic->id,
                        'name'  => $topic->name,
                        'text'  => $topic->text,
                    ];
                })->all(),
                'total' => $this->topics->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
