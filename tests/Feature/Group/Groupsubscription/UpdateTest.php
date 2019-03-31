<?php

namespace Tests\Feature\Group\Groupsubscription;

use App\Groupsubscription;
use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group update
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Groupsubscription */
    protected $groupsubscription;
    /** @var \App\Groupsubscription */
    protected $updatedGroupsubscription;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->groupsubscription = factory(Groupsubscription::class)->create([
            'user_id' => $this->users[0]->id,
            'group_id' => $this->group->id,
        ]);
        $this->updatedGroupsubscription = factory(Groupsubscription::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedGroupsubscription->title,
            'text'  => $this->updatedGroupsubscription->text,
        ];
    }

    public function testUpdateGroupsubscriptionAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->groupsubscription->id,
                'user_id' => $this->groupsubscription->user_id,
                'group_id' => $this->groupsubscription->group->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateGroupsubscriptionPatchWithoutId()
    {
        $this->patchJson('/groups/'.$this->group->id.'/groupsubscriptions', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateGroupsubscriptionAsGuest()
    {
        $this->patchJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateGroupsubscriptionEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id, [])
            ->assertStatus(200)
            ->assertJson([
                'id' => $this->groupsubscription->id,
                'user_id' => $this->groupsubscription->user_id,
                'group_id' => $this->groupsubscription->group->id,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateGroupsubscriptionEmptyNullPayload()
    {
        $this->markTestSkipped();
        $this->actingAs($this->users[0])
            ->patchJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id, [
                'user_id' => null,
                'group_id' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "user_id" => ["The user id field is required."],
                    "group_id" => ["The group id field is required."]
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
