<?php

namespace Tests\Feature\Group\Groupsubscription;

use App\Groupsubscription;
use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\User */
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->user = factory(User::class)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->groupsubscription = factory(Groupsubscription::class)->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
        ]);
    }

    public function testShowGroupsubscriptionAsUser()
    {
        $groupsubscription = $this->groupsubscription;
        $this->actingAs($this->users[0])
            ->getJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id)
            ->assertSuccessful()
            ->assertJson([
                'id' => $groupsubscription->id,
                'user_id' => $groupsubscription->user_id,
                'user' => [
                    'id' => $groupsubscription->user->id,
                    'handle' => $groupsubscription->user->handle,
                    'name' => $groupsubscription->user->name,
                ],
                'group_id' => $groupsubscription->group_id,
                'group' => [
                    'id' => $groupsubscription->group->id,
                    'name' => $groupsubscription->group->name,
                    'text' => $groupsubscription->group->text,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testShowGroupsubscriptionAsGuest()
    {
        $groupsubscription = $this->groupsubscription;
        $this->getJson('/groups/'.$this->group->id.'/groupsubscriptions/'.$this->groupsubscription->id)
            ->assertSuccessful()
            ->assertJson([
                'id' => $groupsubscription->id,
                'user_id' => $groupsubscription->user_id,
                'user' => [
                    'id' => $groupsubscription->user->id,
                    'handle' => $groupsubscription->user->handle,
                    'name' => $groupsubscription->user->name,
                ],
                'group_id' => $groupsubscription->group_id,
                'group' => [
                    'id' => $groupsubscription->group->id,
                    'name' => $groupsubscription->group->name,
                    'text' => $groupsubscription->group->text,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
