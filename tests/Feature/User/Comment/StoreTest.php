<?php

namespace Tests\Feature\User\Comment;

use App\Comment;
use App\Http\Middleware\Idempotency;
use App\User;
use Tests\TestCase;

/**
 * @group store
 * @group comments
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment */
    protected $comment;

    public function setUp()
    {
        parent::setUp();
        $this->users = factory(User::class, 4)->create();
        $this->comment = factory(Comment::class)->make();
        $this->comment->setRelation('op', $this->users[0]);
    }

    protected function getPayload(Comment $comment = null): array {
        if (is_null($comment)) $comment = $this->comment;
        return [
            'title' => $comment->title,
            'text'  => $comment->text,
        ]+array_filter([
            'pc_id' => $comment->pc_id,
        ]);
    }

    public function testStoreCommentAsUserAndReplyAsOtherUser()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/users/'.$this->users[0]->id.'/comments', $this->getPayload())
            ->assertStatus(405)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreCommentAsGuest()
    {
        $this->postJson('/users/'.$this->users[0]->id.'/comments', $this->getPayload())
            ->assertStatus(405);
    }

    public function testStoreCommentEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/users/'.$this->users[0]->id.'/comments', [])
            ->assertStatus(405)
            ->assertDontExposeUserEmails($this->users);
    }
}
