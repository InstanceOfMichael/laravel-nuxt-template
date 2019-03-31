<?php

namespace Tests\Feature\Group\Groupmembership;

use App\Groupmembership;
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
    /** @var \App\Groupmembership */
    protected $groupmembership;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->group->setRelation('op', $this->users[1]);
        $this->users = factory(User::class, 2)->create();

        $this->groupmemberships = $this->users->map(function (User $user) {
            return $this->groupmemberships = factory(Groupmembership::class)->make([
                'user_id' => $user->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[0])
            ->setRelation('group', $this->group)
            ->setRelation('user', $user);
        });
    }

    protected function getPayload(): array {
        return [
            'user_id' => $this->groupmemberships[0]->user_id,
        ];
    }

    public function testStoreGroupmembershipAsUser()
    {
        $groupmembership = $this->groupmemberships[0];
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', $this->getPayload())
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $groupmembership->id,
                'user_id' => $groupmembership->user_id,
                'group_id' => $groupmembership->group_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
    }

    /**
     * @group idempotency
     */
    public function testStoreGroupmembershipAsUserIdempotent()
    {
        $groupmembership = $this->groupmemberships[0];
        $r1 = $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            // ->assertStatus(201)
            ->assertJson([
                // 'id' => $groupmembership->id,
                'user_id' => $groupmembership->user_id,
                'group_id' => $groupmembership->group_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r2 = $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', $this->getPayload(), [
                Idempotency::HEADER => base64_encode(__CLASS__),
            ])
            ->assertStatus(201)
            ->assertJson([
                // 'id' => $groupmembership->id,
                'user_id' => $groupmembership->user_id,
                'group_id' => $groupmembership->group_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "user_id" => ["This user is already a member of this group."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreGroupmembershipAsGuest()
    {
        $this->postJson('/groups/'.$this->group->id.'/groupmemberships', $this->getPayload())
            ->assertStatus(401);
    }

    public function testStoreGroupmembershipEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', [])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "user_id" => ["The user id field is required."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreGroupmembershipEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', [
                "user_id" => null,
                "user_id_list" => null,
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

    public function testStoreGroupmembershipEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', [
                "user_id" => 0,
            ])
            ->assertStatus(422);
    }
}
