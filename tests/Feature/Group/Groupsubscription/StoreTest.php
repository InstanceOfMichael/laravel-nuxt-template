<?php

namespace Tests\Feature\Group\Groupsubscription;

use App\Groupsubscription;
use App\Http\Middleware\Idempotency;
use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\User */
    protected $user;
    /** @var \App\Group */
    protected $group;
    /** @var \App\Groupsubscription */
    protected $groupsubscription;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->group->setRelation('op', $this->users[1]);
        $this->users = factory(User::class, 2)->create();

        $this->groupsubscriptions = $this->users->map(function (User $user) {
            return $this->groupsubscriptions = factory(Groupsubscription::class)->make([
                'user_id' => $user->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('group', $this->group)
            ->setRelation('user', $user);
        });
    }

    protected function getPayload(): array {
        return [
            'user_id' => $this->groupsubscriptions[0]->user_id,
        ];
    }

    public function testStoreGroupsubscriptionAsUser()
    {
        $groupsubscription = $this->groupsubscriptions[0];
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupsubscriptions', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $groupsubscription->id,
                'user_id' => $groupsubscription->user_id,
                'group_id' => $groupsubscription->group_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreGroupsubscriptionAsUserIdempotent()
    {
        $groupsubscription = $this->groupsubscriptions[0];
        $r1 = $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupsubscriptions', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            // ->assertStatus(201)
            ->assertJson([
                // 'id' => $groupsubscription->id,
                'user_id' => $groupsubscription->user_id,
                'group_id' => $groupsubscription->group_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r2 = $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupsubscriptions', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $groupsubscription->id,
                'user_id' => $groupsubscription->user_id,
                'group_id' => $groupsubscription->group_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupsubscriptions', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "user_id" => ["This user is already subscribed to this group."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreGroupsubscriptionAsGuest()
    {
        $this->postJson('/groups/'.$this->group->id.'/groupsubscriptions', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreGroupsubscriptionEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupsubscriptions', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "user_id" => ["The user id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreGroupsubscriptionEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupsubscriptions', [
                "user_id" => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "user_id" => ["The user id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreGroupsubscriptionEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupsubscriptions', [
                "user_id" => 0,
            ])
            ->assertStatus(422);
    }

}
