<?php

namespace Tests\Feature\Definition;

use App\User;
use App\Definition;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Definition */
    protected $definition;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->definition = factory(Definition::class)->create();
    }

    public function testShowDefinitionAsUser()
    {
        $definition = $this->definition;
        $this->actingAs($this->user)
            ->getJson('/definitions/'.$this->definition->id)
            ->assertStatus(200)
            ->assertJson([
                'id'    => $definition->id,
                'name'  => $definition->name,
                'text'  => $definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testShowDefinitionAsGuest()
    {
        $definition = $this->definition;
        $this->getJson('/definitions/'.$this->definition->id)
            ->assertStatus(200)
            ->assertJson([
                'id'    => $definition->id,
                'name'  => $definition->name,
                'text'  => $definition->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
