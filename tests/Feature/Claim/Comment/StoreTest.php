<?php

namespace Tests\Feature\Claim\Comment;

use App\User;
use App\Claim;
use App\Comment;
use App\Http\Middleware\Idempotency;
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

    /**
     * @group idempotency
     */
    public function testStoreClaimCommentAsUserIdempotent()
    {
        $r1 = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'text'  => $this->comment->text,
                'pc_id' => $this->comment->pc_id,
                'op_id' => $this->comment->op->id,
            ])
            ->assertDontExposeUserEmails($this->users);
        $r2 = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                'text'  => $this->comment->text,
                'pc_id' => $this->comment->pc_id,
                'op_id' => $this->comment->op->id,
            ])
            ->assertDontExposeUserEmails($this->users);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'text'  => $this->comment->text,
                'pc_id' => $this->comment->pc_id,
                'op_id' => $this->comment->op->id,
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
    }

    public function testStoreClaimCommentAsUserAndReplyAsOtherUserWithInvalidParentCommentId()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'text'  => $this->comment->text,
                'pc_id' => $this->comment->pc_id,
                'op_id' => $this->comment->op->id,
            ])
            ->assertDontExposeUserEmails($this->users);

        $this->assertNotNull($pc = Comment::find($r->json('id')));

        $nextComment = factory(Comment::class)->make([
            'pc_id' => $pc->id,
            'op_id' => $this->users[1]->id,
        ]);
        $nextComment->setRelation('op', $this->users[1]);

        // you can't make a "question comment" reply to a comment on a non-question
        $this->actingAs($this->users[1])
            ->postJson('/questions/'.$this->question->id.'/comments', $this->getPayload($nextComment))
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "pc_id" => ["Replying to comment that does not exist."],
                ],
                "message" => "The given data was invalid.",
            ]);
    }

    public function testStoreClaimCommentAsUserAndReplyAsOtherUser()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'text'  => $this->comment->text,
                'pc_id' => $this->comment->pc_id,
                'op_id' => $this->comment->op->id,
            ])
            ->assertDontExposeUserEmails($this->users);

        $this->assertNotNull($pc = Comment::find($r->json('id')));

        $nextComment = factory(Comment::class)->make([
            'pc_id' => $pc->id,
            'op_id' => $this->users[1]->id,
        ]);
        $nextComment->setRelation('op', $this->users[1]);

        $this->actingAs($this->users[1])
            ->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload($nextComment))
            ->assertStatus(201)
            ->assertJson([
                'text'  => $nextComment->text,
                'pc_id' => $nextComment->pc_id,
                'op_id' => $nextComment->op->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreClaimCommentAsGuest()
    {
        $this->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreCommentEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claims/'.$this->claim->id.'/comments', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text field is required."],
                ],
                "message" => "The given data was invalid.",
            ]);
    }
}
