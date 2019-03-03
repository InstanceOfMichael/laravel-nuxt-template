<?php

namespace Tests\Feature\Question;

use App\User;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ShowTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Question */
    protected $question;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->user->id,
        ]);
    }

    public function testShowQuestionAsUser()
    {
        $this->actingAs($this->user)
            ->getJson('/questions/'.$this->question->id)
            ->assertSuccessful()
            ->assertJson([
                'title' => $this->question->title,
                'text'  => $this->question->text,
                'op_id' => $this->question->op_id,
                'op' => [
                    'id'     => $this->question->op->id,
                    'handle' => $this->question->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testShowQuestionAsGuest()
    {
        $this->getJson('/questions/'.$this->question->id)
            ->assertSuccessful()
            ->assertJson([
                'title' => $this->question->title,
                'text'  => $this->question->text,
                'op_id' => $this->question->op_id,
                'op' => [
                    'id'     => $this->question->op->id,
                    'handle' => $this->question->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }
}
