<?php

namespace Tests\Feature\User\Comment;

use App\Claim;
use App\Comment;
use App\Http\Middleware\Idempotency;
use App\User;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group store
 * @group comments
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Comment */
    protected $comment;

    public function setUp()
    {
        parent::setUp();
        $this->users = factory(User::class, 4)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[0]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
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

    public function testStoreClaimCommentAsUserAndReplyAsOtherUser()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/users/'.$this->users[0]->id.'/comments', $this->getPayload())
            ->assertStatus(405)
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreClaimCommentAsGuest()
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
