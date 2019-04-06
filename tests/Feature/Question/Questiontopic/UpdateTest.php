<?php

namespace Tests\Feature\Question\Questiontopic;

use App\Questiontopic;
use App\User;
use App\Topic;
use App\Question;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic */
    protected $topic;
    /** @var \App\Questiontopic */
    protected $questiontopic;
    /** @var \App\Questiontopic */
    protected $updatedQuestiontopic;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->topic = factory(Topic::class)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->questiontopic = factory(Questiontopic::class)->create([
            'op_id' => $this->users[0]->id,
            'topic_id' => $this->topic->id,
            'question_id' => $this->question->id,
        ]);
        $this->updatedQuestiontopic = factory(Questiontopic::class)->make([
            'op_id' => $this->users[0]->id,
        ]);
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedQuestiontopic->title,
            'text'  => $this->updatedQuestiontopic->text,
        ];
    }

    public function testUpdateQuestiontopicAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/questions/'.$this->question->id.'/questiontopics/'.$this->questiontopic->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->questiontopic->id,
                'op_id' => $this->questiontopic->op->id,
                'topic_id' => $this->questiontopic->topic_id,
                'question_id' => $this->questiontopic->question->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateQuestiontopicPatchWithoutId()
    {
        $this->patchJson('/questions/'.$this->question->id.'/questiontopics', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateQuestiontopicAsGuest()
    {
        $this->patchJson('/questions/'.$this->question->id.'/questiontopics/'.$this->questiontopic->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateQuestiontopicEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/questions/'.$this->question->id.'/questiontopics/'.$this->questiontopic->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->questiontopic->id,
                'op_id' => $this->questiontopic->op->id,
                'topic_id' => $this->questiontopic->topic_id,
                'question_id' => $this->questiontopic->question->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateQuestiontopicEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/questions/'.$this->question->id.'/questiontopics/'.$this->questiontopic->id, [
                'topic_id' => null,
                'question_id' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["The topic id field is required."],
                    "question_id" => ["The question id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
