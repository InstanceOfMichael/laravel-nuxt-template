<?php

namespace Tests\Feature\Definition\Comment;

use App\User;
use App\Claim;
use App\Comment;
use App\Definition;
use Tests\TestCase;

/**
 * @group show
 * @group comments
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment[] */
    protected $comments;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Definition */
    protected $definition;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->definition = factory(Definition::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->comments = collect([
            $this->definition->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[2]->id,
            ])),
            $this->claim->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[3]->id,
            ])),
        ]);
    }

    public function testShowDefinitionCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/definitions/'.$this->definition->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    public function testShowDefinitionCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/definitions/'.$this->definition->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
