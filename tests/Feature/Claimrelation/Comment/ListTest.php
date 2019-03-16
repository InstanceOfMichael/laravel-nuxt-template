<?php

namespace Tests\Feature\Claimrelation\Comment;

use App\Claimrelation;
use App\User;
use App\Claim;
use App\Comment;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group list
 * @group comments
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\CommentTopic[] */
    protected $commentables;
    /** @var \App\Claim[] */
    protected $claims;
    /** @var \App\Claimrelation */
    protected $claimrelation;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();

        $this->claims = collect([
            factory(Claim::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            factory(Claim::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
        ]);

        $this->commentables = collect([
            $this->claims[0],
            $this->claims[1],
            $this->claimrelation = factory(Claimrelation::class)->create([
                'op_id' => factory(User::class)->create()->id,
                'pc_id' => $this->claims[0]->id,
                'rc_id' => $this->claims[1]->id,
            ]),
        ]);

        $this->commentables->each(function ($commentable) {
            $commentable->comments()->create(factory(Comment::class)->raw([ 'op_id' => factory(User::class)->create()->id ]));
            $commentable->comments()->create(factory(Comment::class)->raw([ 'op_id' => factory(User::class)->create()->id ]));
        });

        $this->commentables->map(function ($commentable) {
            $commentable->comments()->create(factory(Comment::class)->raw([
                'op_id' => factory(User::class)->create()->id,
                'pc_id' => $commentable->comments()->first()->id,
            ]));
        });

        $this->comments = Comment::query()
            ->take(9)
            ->with('op')
            ->orderBy('comments.id', 'desc')
            ->get();

        $this->assertEquals(9, Comment::query()->count());
    }

    public function testListClaimrelationCommentsAsUser()
    {
        $comments = $this->comments
            ->where('topic_type', $this->claimrelation->getMorphClass())
            ->where('topic_id', $this->claimrelation->id)
            ->sortByDesc('id')
            ->values();
        $this->actingAs($this->users[0])
            ->getJson('/claimrelations/'.$this->claimrelation->id.'/comments')
            ->assertSuccessful()
            ->assertJson([
                'data' => $comments->map(function (Comment $c):array {
                    return [
                        'id'    => $c->id,
                        'text'  => $c->text,
                        'pc_id' => $c->pc_id,
                        'op_id' => $c->op_id,
                        'op' => [
                            'id'     => $c->op->id,
                            'handle' => $c->op->handle,
                        ],
                    ];
                })->all(),
                'total' => $comments->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListClaimrelationCommentsAsGuest()
    {
        $comments = $this->comments
            ->where('topic_type', $this->claimrelation->getMorphClass())
            ->where('topic_id', $this->claimrelation->id)
            ->sortByDesc('id')
            ->values();
        $this->getJson('/claimrelations/'.$this->claimrelation->id.'/comments')
            ->assertSuccessful()
            ->assertJson([
                'data' => $comments->map(function (Comment $c):array {
                    return [
                        'id'    => $c->id,
                        'text'  => $c->text,
                        'pc_id' => $c->pc_id,
                        'op_id' => $c->op_id,
                        'op' => [
                            'id'     => $c->op->id,
                            'handle' => $c->op->handle,
                        ],
                    ];
                })->all(),
                'total' => $comments->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
