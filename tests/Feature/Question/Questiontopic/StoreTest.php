<?php

namespace Tests\Feature\Question\Questiontopic;

use App\Questiontopic;
use App\Http\Middleware\Idempotency;
use App\User;
use App\Topic;
use App\Question;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Topic */
    protected $topic;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Questiontopic */
    protected $questiontopic;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->question->setRelation('op', $this->users[1]);
        $this->topics = factory(Topic::class, 2)->create();

        $this->questiontopics = $this->topics->map(function (Topic $topic) {
            return $this->questiontopics = factory(Questiontopic::class)->make([
                'op_id' => $this->users[0]->id,
                'topic_id' => $topic->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[0])
            ->setRelation('question', $this->question)
            ->setRelation('topic', $topic);
        });
    }

    protected function getPayload(): array {
        return [
            'topic_id' => $this->questiontopics[0]->topic_id,
        ];
    }

    protected function getBulkPayload(): array {
        return [
            'topic_id_list' => $this->questiontopics->pluck('topic_id')->values()->all(),
        ];
    }

    public function testStoreQuestiontopicAsUser()
    {
        $questiontopic = $this->questiontopics[0];
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/questiontopics', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $questiontopic->id,
                'op_id' => $questiontopic->op->id,
                'topic_id' => $questiontopic->topic_id,
                'question_id' => $questiontopic->question_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreQuestiontopicAsUserIdempotent()
    {
        $questiontopic = $this->questiontopics[0];
        $r1 = $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/questiontopics', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            // ->assertStatus(201)
            ->assertJson([
                // 'id' => $questiontopic->id,
                'op_id' => $questiontopic->op->id,
                'topic_id' => $questiontopic->topic_id,
                'question_id' => $questiontopic->question_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r2 = $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/questiontopics', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $questiontopic->id,
                'op_id' => $questiontopic->op->id,
                'topic_id' => $questiontopic->topic_id,
                'question_id' => $questiontopic->question_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/questiontopics', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["This topic is already associated with this question."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreQuestiontopicAsGuest()
    {
        $this->postJson('/questions/'.$this->question->id.'/questiontopics', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreQuestiontopicEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/questiontopics', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["The topic id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreQuestiontopicEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/questiontopics', [
                "topic_id" => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "topic_id" => ["The topic id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreQuestiontopicEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/questiontopics', [
                "topic_id" => 0,
            ])
            ->assertStatus(422);
    }
}
