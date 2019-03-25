<?php

namespace Tests\Feature\Question;

use App\User;
use App\Question;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Question[] */
    protected $questions;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->questions = collect([
            factory(Question::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Question::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Question::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
    }

    public function testListQuestionAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/questions')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->questions->reverse()->map(function (Question $q):array {
                    return [
                        'id'    => $q->id,
                        'title'  => $q->title,
                        'text'  => $q->text,
                        'sides_type'  => $q->sides_type,
                        'op_id' => $q->op_id,
                        'op' => [
                            'id'     => $q->op->id,
                            'handle' => $q->op->handle,
                        ],
                    ];
                })->values()->all(),
                'total' => $this->questions->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListQuestionAsGuest()
    {
        $this->getJson('/questions')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->questions->reverse()->map(function (Question $q):array {
                    return [
                        'id'    => $q->id,
                        'title'  => $q->title,
                        'text'  => $q->text,
                        'sides_type'  => $q->sides_type,
                        'op_id' => $q->op_id,
                        'op' => [
                            'id'     => $q->op->id,
                            'handle' => $q->op->handle,
                        ],
                    ];
                })->values()->all(),
                'total' => $this->questions->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
