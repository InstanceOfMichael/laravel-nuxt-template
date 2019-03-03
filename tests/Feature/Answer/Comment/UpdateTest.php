<?php

namespace Tests\Feature\Answer\Comment;

use App\Answer;
use App\User;
use App\Claim;
use App\Comment;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment */
    protected $comment;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Answer */
    protected $answer;
    /** @var \App\Comment */
    protected $updatedComment;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->question->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->question->comments[0]->setRelation('op', $this->users[0]);
        $this->claim->comments[0]->setRelation('op', $this->users[0]);

        $this->answer = factory(Answer::class)->create([
            'op_id' => $this->users[0]->id,
            'claim_id' => $this->claim->id,
            'question_id' => $this->question->id,
        ]);
        $this->answer->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->answer->comments[0]->setRelation('op', $this->users[0]);

        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedComment->title,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateAnswerCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->answer->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->answer->comments[0]->op->id,
                'op' => [
                    'id'     => $this->answer->comments[0]->op->id,
                    'handle' => $this->answer->comments[0]->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateAnswerCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/comments/'.$this->answer->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateQuestionCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/answers/'.$this->answer->id.'/comments/'.$this->question->comments[0]->id, $this->getPayload())
            ->assertStatus(405);
    }
}
