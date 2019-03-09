<?php

namespace Tests\Feature\Question\Allowedquestionside;

use App\Allowedquestionside;
use App\User;
use App\Side;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side */
    protected $side;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Allowedquestionside */
    protected $allowedquestionside;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->question->setRelation('op', $this->users[1]);
        $this->sides = factory(Side::class, 2)->create([
            'op_id' => $this->users[2]->id,
        ]);

        foreach($this->sides as $side) {
            $side->setRelation('op', $this->users[2]);
        }
        $this->allowedquestionsides = $this->sides->map(function (Side $side) {
            return $this->allowedquestionsides = factory(Allowedquestionside::class)->make([
                'op_id' => $this->users[0]->id,
                'side_id' => $side->id,
                'question_id' => $this->question->id,
            ])
            ->setRelation('op', $this->users[0])
            ->setRelation('question', $this->question)
            ->setRelation('side', $side);
        });
    }

    protected function getPayload(): array {
        return [
            'side_id' => $this->allowedquestionsides[0]->side_id,
        ];
    }

    protected function getBulkPayload(): array {
        return [
            'side_id_list' => $this->allowedquestionsides->pluck('side_id')->values()->all(),
        ];
    }

    public function testStoreAllowedquestionsideAsUser()
    {
        $allowedquestionside = $this->allowedquestionsides[0];
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/allowedquestionsides', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $allowedquestionside->id,
                'op_id' => $allowedquestionside->op->id,
                'side_id' => $allowedquestionside->side_id,
                'question_id' => $allowedquestionside->question_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    public function testStoreBulkAllowedquestionsideAsUser()
    {
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/allowedquestionsides', $this->getBulkPayload())
            ->assertStatus(201)
            ->assertJson($this->allowedquestionsides->map(function (Allowedquestionside $allowedquestionside) {
                return [
                    // 'id' => $allowedquestionside->id,
                    'op_id' => $allowedquestionside->op->id,
                    'side_id' => $allowedquestionside->side_id,
                    'question_id' => $allowedquestionside->question_id,
                ];
            })->values()->all())
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    public function testStoreAllowedquestionsideAsGuest()
    {
        $this->postJson('/questions/'.$this->question->id.'/allowedquestionsides', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreAllowedquestionsideEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/allowedquestionsides', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["The side id field is required when side id list is not present."],
                    "side_id_list" => ["The side id list field is required when side id is not present."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreAllowedquestionsideEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/allowedquestionsides', [
                "side_id" => null,
                "side_id_list" => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["The side id field is required when side id list is not present."],
                    "side_id_list" => ["The side id list field is required when side id is not present."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreAllowedquestionsideEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/questions/'.$this->question->id.'/allowedquestionsides', [
                "side_id" => 0,
                "side_id_list" => [0],
            ])
            ->assertStatus(404);
    }
}
