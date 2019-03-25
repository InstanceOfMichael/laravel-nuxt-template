<?php

namespace Tests\Feature\Group;

use App\User;
use App\Group;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Group */
    protected $group;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->group = factory(Group::class)->create([
            'op_id' => $this->user->id,
        ]);
    }

    public function testShowGroupAsUser()
    {
        $this->actingAs($this->user)
            ->getJson('/groups/'.$this->group->id)
            ->assertSuccessful()
            ->assertJson([
                'title' => $this->group->title,
                'text'  => $this->group->text,
                'sides_type'  => $this->group->sides_type,
                'op_id' => $this->group->op_id,
                'op' => [
                    'id'     => $this->group->op->id,
                    'handle' => $this->group->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }

    public function testShowGroupAsGuest()
    {
        $this->getJson('/groups/'.$this->group->id)
            ->assertSuccessful()
            ->assertJson([
                'title' => $this->group->title,
                'text'  => $this->group->text,
                'sides_type'  => $this->group->sides_type,
                'op_id' => $this->group->op_id,
                'op' => [
                    'id'     => $this->group->op->id,
                    'handle' => $this->group->op->handle,
                ],
            ])
            ->assertDontSeeText($this->user->email)
            ->assertJsonMissing(['email']);
    }
}
