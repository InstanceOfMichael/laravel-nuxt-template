<?php

namespace Tests\Feature\Question\Questiontopic;

use App\Questiontopic;
use App\User;
use App\Topic;
use App\Question;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic[] */
    protected $topics;
    /** @var \App\Questiontopic[] */
    protected $questiontopics;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();
        $this->topics = collect([
            factory(Topic::class)->create(),
            factory(Topic::class)->create(),
            factory(Topic::class)->create(),
        ]);
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[3]->id,
        ]);
        $this->questiontopics = collect([
            factory(Questiontopic::class)->create([
                'op_id' => $this->users[6]->id,
                'topic_id' => $this->topics[0]->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[6])
            ->setRelation('topic', $this->topics[0])
            ->setRelation('question', $this->question),
            factory(Questiontopic::class)->create([
                'op_id' => $this->users[7]->id,
                'topic_id' => $this->topics[1]->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[7])
            ->setRelation('topic', $this->topics[1])
            ->setRelation('question', $this->question),
            factory(Questiontopic::class)->create([
                'op_id' => $this->users[8]->id,
                'topic_id' => $this->topics[2]->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[8])
            ->setRelation('topic', $this->topics[2])
            ->setRelation('question', $this->question),
        ])->sortByDesc('id')->values();
    }

    public function testListQuestiontopicAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/questions/'.$this->question->id.'/questiontopics')
            ->assertStatus(200)
            ->assertJson($this->questiontopics->map(function (Questiontopic $questiontopic):array {
                return [
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
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListQuestiontopicAsGuest()
    {
        $this->getJson('/questions/'.$this->question->id.'/questiontopics')
            ->assertStatus(200)
            ->assertJson($this->questiontopics->map(function (Questiontopic $questiontopic):array {
                return [
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
                ];
            })->all())
            ->assertDontExposeUserEmails($this->users);
    }
}
