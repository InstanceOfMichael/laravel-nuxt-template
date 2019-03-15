<?php

namespace Tests\Feature\Claimside\Comment;

use App\Claimside;
use App\User;
use App\Claim;
use App\Comment;
use App\Side;
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
    /** @var \App\Claimside */
    protected $claimside;

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
        $this->claimside = factory(Claimside::class)->create([
            'op_id' => $this->users[2]->id,
            'claim_id' => $this->claim->id,
            'side_id' => $this->side->id,
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
     * @group comment
     */
    public function testStoreClaimsideCommentAsUserAndReplyAsOtherUserWithInvalidParentCommentId()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/sides/'.$this->side->id.'/comments', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'text'  => $this->comment->text,
                'pc_id' => $this->comment->pc_id,
                'op_id' => $this->comment->op->id,
                'op' => [
                    'id'     => $this->comment->op->id,
                    'handle' => $this->comment->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);

        $this->assertNotNull($pc = Comment::find($r->json('id')));

        $nextComment = factory(Comment::class)->make([
            'pc_id' => $pc->id,
            'op_id' => $this->users[1]->id,
        ]);
        $nextComment->setRelation('op', $this->users[1]);

        // you can't make a "claimside comment" reply to a comment on a non-claimside
        $this->actingAs($this->users[1])
            ->postJson('/claimsides/'.$this->claimside->id.'/comments', $this->getPayload($nextComment))
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
    public function testStoreClaimsideCommentAsUserAndReplyAsOtherUser()
    {
        $r = $this->actingAs($this->users[0])
            ->postJson('/claimsides/'.$this->claimside->id.'/comments', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                'text'  => $this->comment->text,
                'pc_id' => $this->comment->pc_id,
                'op_id' => $this->comment->op->id,
                'op' => [
                    'id'     => $this->comment->op->id,
                    'handle' => $this->comment->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);

        $this->assertNotNull($pc = Comment::find($r->json('id')));

        $nextComment = factory(Comment::class)->make([
            'pc_id' => $pc->id,
            'op_id' => $this->users[1]->id,
        ]);
        $nextComment->setRelation('op', $this->users[1]);

        $this->actingAs($this->users[1])
            ->postJson('/claimsides/'.$this->claimside->id.'/comments', $this->getPayload($nextComment))
            ->assertStatus(201)
            ->assertJson([
                'text'  => $nextComment->text,
                'pc_id' => $nextComment->pc_id,
                'op_id' => $nextComment->op->id,
                'op' => [
                    'id'     => $nextComment->op->id,
                    'handle' => $nextComment->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    /**
     * @group comment
     */
    public function testStoreClaimsideCommentAsGuest()
    {
        $this->postJson('/claimsides/'.$this->claimside->id.'/comments', $this->getPayload())
            ->assertStatus(401);
    }

    /**
     * @group comment
     */
    public function testStoreCommentEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/claimsides/'.$this->claimside->id.'/comments', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text field is required."],
                ],
                "message" => "The given data was invalid.",
            ]);
    }
}