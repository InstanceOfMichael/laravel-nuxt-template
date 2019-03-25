<?php

namespace Tests\Feature\Group\Groupmembership;

use App\Groupmembership;
use App\Http\Middleware\Idempotency;
use App\User;
use App\Side;
use App\Group;
use Tests\TestCase;

/**
 * @group store
 */
class StoreTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Side */
    protected $side;
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
        $this->sides = factory(Side::class, 2)->create([
            'op_id' => $this->users[2]->id,
        ]);

        foreach($this->sides as $side) {
            $side->setRelation('op', $this->users[2]);
        }
        $this->groupmemberships = $this->sides->map(function (Side $side) {
            return $this->groupmemberships = factory(Groupmembership::class)->make([
                'op_id' => $this->users[0]->id,
                'side_id' => $side->id,
                'group_id' => $this->group->id,
            ])
            ->setRelation('op', $this->users[0])
            ->setRelation('group', $this->group)
            ->setRelation('side', $side);
        });
    }

    protected function getPayload(): array {
        return [
            'side_id' => $this->groupmemberships[0]->side_id,
        ];
    }

    protected function getBulkPayload(): array {
        return [
            'side_id_list' => $this->groupmemberships->pluck('side_id')->values()->all(),
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
                'op_id' => $groupmembership->op->id,
                'side_id' => $groupmembership->side_id,
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
                'op_id' => $groupmembership->op->id,
                'side_id' => $groupmembership->side_id,
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
                'op_id' => $groupmembership->op->id,
                'side_id' => $groupmembership->side_id,
                'group_id' => $groupmembership->group_id,
            ])
            ->assertDontExposeUserEmails($this->users[0]->email);
        $r3 = $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', $this->getPayload())
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "side_id" => ["This side is already associated with this group."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
        $this->assertEquals($r1->json('id'), $r2->json('id'));
        $this->assertNotEquals($r1->json('id'), $r3->json('id'));
        $this->assertNull($r3->json('id'));
    }

    public function testStoreBulkGroupmembershipAsUser()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', $this->getBulkPayload())
            ->assertStatus(201)
            ->assertJson($this->groupmemberships->map(function (Groupmembership $groupmembership) {
                return [
                    // 'id' => $groupmembership->id,
                    'op_id' => $groupmembership->op->id,
                    'side_id' => $groupmembership->side_id,
                    'group_id' => $groupmembership->group_id,
                ];
            })->values()->all())
            ->assertDontExposeUserEmails($this->users[0]->email);
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
                    "side_id" => ["The side id field is required when side id list is not present."],
                    "side_id_list" => ["The side id list field is required when side id is not present."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testStoreGroupmembershipEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', [
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

    public function testStoreGroupmembershipEmptyZeroPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', [
                "side_id" => 0,
            ])
            ->assertStatus(422);
    }

    public function testStoreGroupmembershipEmptyZeroBulkPayload()
    {
        $this->actingAs($this->users[0])
            ->postJson('/groups/'.$this->group->id.'/groupmemberships', [
                "side_id_list" => [0],
            ])
            ->assertStatus(404);
    }
}
