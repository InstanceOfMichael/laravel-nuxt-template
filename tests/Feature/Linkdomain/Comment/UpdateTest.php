<?php

namespace Tests\Feature\Linkdomain\Comment;

use App\User;
use App\Comment;
use App\Claim;
use App\Link;
use Tests\TestCase;

/**
 * @group update
 * @group comments
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Comment */
    protected $comment;
    /** @var \App\Comment */
    protected $updatedComment;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->link = factory(Link::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->link->Linkdomain->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->link->linkdomain->comments[0]->setRelation('op', $this->users[0]);
        $this->claim->comments[0]->setRelation('op', $this->users[0]);
        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedComment->title,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateLinkCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->link->linkdomain->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->link->linkdomain->comments[0]->op->id,
                'op' => [
                    'id'     => $this->link->linkdomain->comments[0]->op->id,
                    'handle' => $this->link->linkdomain->comments[0]->op->handle,
                ],
            ])
            ->assertDontSeeText($this->users[0]->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateLinkCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/linkdomains/'.$this->link->linkdomain->id.'/comments/'.$this->link->linkdomain->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateLinkCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/linkdomains/'.$this->link->linkdomain->id.'/comments/'.$this->link->linkdomain->comments[0]->id, $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateLinkCommentAsGuest()
    {
        $this->patchJson('/comments/'.$this->link->linkdomain->comments[0]->id, $this->getPayload())
            ->assertStatus(401);
    }
}
