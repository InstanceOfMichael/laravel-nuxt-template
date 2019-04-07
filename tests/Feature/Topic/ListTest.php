<?php

namespace Tests\Feature\Definition;

use App\User;
use App\Definition;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Definition[] */
    protected $definitions;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->definitions = collect([
            factory(Definition::class)->create(),
            factory(Definition::class)->create(),
            factory(Definition::class)->create(),
        ])->sortByDesc('id')->values();
    }

    public function testListDefinitionAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/definitions')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->definitions->map(function (Definition $definition):array {
                    return [
                        'id'    => $definition->id,
                        'name'  => $definition->name,
                        'text'  => $definition->text,
                    ];
                })->all(),
                'total' => $this->definitions->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListDefinitionAsGuest()
    {
        $this->getJson('/definitions')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->definitions->map(function (Definition $definition):array {
                    return [
                        'id'    => $definition->id,
                        'name'  => $definition->name,
                        'text'  => $definition->text,
                    ];
                })->all(),
                'total' => $this->definitions->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
