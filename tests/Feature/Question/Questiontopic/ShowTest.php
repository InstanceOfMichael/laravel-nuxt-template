<?php

namespace Tests\Feature\Question\Questiontopic;

use App\Questiontopic;
use App\User;
use App\Topic;
use App\Question;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic */
    protected $topic;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->topic = factory(Topic::class)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->questiontopic = factory(Questiontopic::class)->create([
            'op_id' => $this->users[0]->id,
            'topic_id' => $this->topic->id,
            'question_id' => $this->question->id,
        ]);
    }

    public function testShowQuestiontopicAsUser()
    {
        $questiontopic = $this->questiontopic;
        $this->actingAs($this->users[0])
            ->getJson('/questions/'.$this->question->id.'/questiontopics/'.$this->questiontopic->id)
            ->assertStatus(200)
            ->assertJson([
                'id' => $questiontopic->id,
                'op_id' => $questiontopic->op->id,
                'op' => [
                    'id'     => $questiontopic->op->id,
                    'handle' => $questiontopic->op->handle,
                ],
                'topic_id' => $questiontopic->topic_id,
                'topic' => [
                    'id' => $questiontopic->topic->id,
                    'name' => $questiontopic->topic->name,
                    'text'  => $questiontopic->topic->text,
                ],
                'question_id' => $questiontopic->question_id,
                'question' => [
                    'id' => $questiontopic->question->id,
                    'title' => $questiontopic->question->title,
                    'text'  => $questiontopic->question->text,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowQuestiontopicAsGuest()
    {
        $questiontopic = $this->questiontopic;
        $this->getJson('/questions/'.$this->question->id.'/questiontopics/'.$this->questiontopic->id)
            ->assertStatus(200)
            ->assertJson([
                'id' => $questiontopic->id,
                'op_id' => $questiontopic->op->id,
                'op' => [
                    'id'     => $questiontopic->op->id,
                    'handle' => $questiontopic->op->handle,
                ],
                'topic_id' => $questiontopic->topic_id,
                'topic' => [
                    'id' => $questiontopic->topic->id,
                    'name' => $questiontopic->topic->name,
                    'text'  => $questiontopic->topic->text,
                ],
                'question_id' => $questiontopic->question_id,
                'question' => [
                    'id' => $questiontopic->question->id,
                    'title' => $questiontopic->question->title,
                    'text'  => $questiontopic->question->text,
                    'op_id' => $questiontopic->question->op->id,
                    'op' => [
                        'id'     => $questiontopic->question->op->id,
                        'handle' => $questiontopic->question->op->handle,
                    ],
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
