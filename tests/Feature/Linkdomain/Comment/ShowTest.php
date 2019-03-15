<?php

namespace Tests\Feature\Linkdomain\Comment;

use App\User;
use App\Claim;
use App\Comment;
use App\Link;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment[] */
    protected $comments;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Link */
    protected $link;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->link = factory(Link::class)->create([
            'op_id' => $this->users[0]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->comments = collect([
            $this->link->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[2]->id,
            ])),
            $this->claim->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[3]->id,
            ])),
        ]);
    }

    /**
     * @group comment
     */
    public function testShowLinkCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/linkdomains/'.$this->link->linkdomain->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    /**
     * @group comment
     */
    public function testShowLinkCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/linkdomains/'.$this->link->linkdomain->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
