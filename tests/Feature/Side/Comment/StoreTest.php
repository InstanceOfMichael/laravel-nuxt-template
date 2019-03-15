<?php

namespace Tests\Feature\Side\Comment;

use App\Claim;
use App\Comment;
use App\Http\Middleware\Idempotency;
use App\Side;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Side */
    protected $side;
    /** @var \App\Comment */
    protected $comment;

    public function setUp()
    {
        parent::setUp();
        $this->users = factory(User::class, 4)->create();
        $this->side = factory(Side::class)->create([
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
            'name' => $comment->name,
            'text'  => $comment->text,
        ]+array_filter([
            'pc_id' => $comment->pc_id,
        ]);
    }

    /**
     * @group comment
     */
    public function testStoreSideCommentAsUserAndReplyAsOtherUser()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/sides/'.$this->side->id.'/comments', $this->getPayload())
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
            ->postJson('/sides/'.$this->side->id.'/comments', $this->getPayload($nextComment))
            ->assertStatus(201)
            ->assertJson([
                'text'  => $nextComment->text,
                'pc_id' => $nextComment->pc_id,
                'op_id' => $nextComment->op->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    /**
     * @group comment
     */
    public function testStoreSideCommentAsUserAndReplyAsOtherUserWithInvalidParentCommentId()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/sides/'.$this->side->id.'/comments', $this->getPayload())
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

        // you can't make a "side comment" reply to a comment on a non-side
        $this->actingAs($this->users[1])
            ->postJson('/claims/'.$this->claim->id.'/comments', $this->getPayload($nextComment))
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "pc_id" => ["Replying to comment that does not exist."],
                ],
                "message" => "The given data was invalid.",
            ]);
    }

    /**
     * @group comment
     */
    public function testStoreSideCommentAsGuest()
    {
        $this->postJson('/sides/'.$this->side->id.'/comments', $this->getPayload())
            ->assertStatus(401);
    }

    /**
     * @group comment
     */
    public function testStoreCommentEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/sides/'.$this->side->id.'/comments', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text field is required."],
                ],
                "message" => "The given data was invalid.",
            ]);
    }
}
