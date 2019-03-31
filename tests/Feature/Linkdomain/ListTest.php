<?php

namespace Tests\Feature\Linkdomain;

use App\User;
use App\Link;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Link[] */
    protected $links;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 3)->create();
        $this->links = collect([
            factory(Link::class)->create([ 'op_id' => $this->users[0]->id ]),
            factory(Link::class)->create([ 'op_id' => $this->users[1]->id ]),
            factory(Link::class)->create([ 'op_id' => $this->users[2]->id ]),
        ]);
    }

    public function testListLinkAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/linkdomains')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->links->map(function (Link $link):array {
                    return [
                        'id'     => $link->linkdomain->id,
                        'domain' => $link->linkdomain->domain,
                        'text' => $link->linkdomain->text,
                    ];
                })->all(),
                'total' => $this->links->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListLinkAsGuest()
    {
        $this->getJson('/linkdomains')
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->links->map(function (Link $link):array {
                    return [
                        'id'     => $link->linkdomain->id,
                        'domain' => $link->linkdomain->domain,
                        'text' => $link->linkdomain->text,
                    ];
                })->all(),
                'total' => $this->links->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
