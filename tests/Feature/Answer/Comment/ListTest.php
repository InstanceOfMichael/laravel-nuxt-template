<?php

namespace Tests\Feature\Answer\Comment;

use App\Answer;
use App\User;
use App\Claim;
use App\Comment;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\CommentTopic[] */
    protected $commentables;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Answer */
    protected $answer;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();

        $this->commentables = collect([
            $this->question = factory(Question::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            $this->claim = factory(Claim::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            $this->answer = factory(Answer::class)->create([
                'op_id' => factory(User::class)->create()->id,
                'claim_id' => $this->claim->id,
                'question_id' => $this->question->id,
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

    /**
     * @group comment
     */
    public function testListAnswerCommentsAsUser()
    {
        $comments = $this->comments
            ->where('topic_type', $this->answer->getMorphClass())
            ->where('topic_id', $this->answer->id)
            ->sortByDesc('id')
            ->values();
        $this->actingAs($this->users[0])
            ->getJson('/answers/'.$this->answer->id.'/comments')
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

    /**
     * @group comment
     */
    public function testListAnswerCommentsAsGuest()
    {
        $comments = $this->comments
            ->where('topic_type', $this->answer->getMorphClass())
            ->where('topic_id', $this->answer->id)
            ->sortByDesc('id')
            ->values();
        $this->getJson('/answers/'.$this->answer->id.'/comments')
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
