<?php

namespace Tests\Feature\Link;

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
            ->getJson('/links')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->links->map(function (Link $link):array {
                    return [
                        'id'    => $link->id,
                        'title'  => $link->title,
                        'url'  => $link->url,
                        'op_id' => $link->op_id,
                        'op' => [
                            'id'     => $link->op->id,
                            'handle' => $link->op->handle,
                        ],
                        'ld_id' => $link->ld_id,
                        'linkdomain' => [
                            'id'     => $link->linkdomain->id,
                            'domain' => $link->linkdomain->domain,
                            'text' => $link->linkdomain->text,
                        ],
                    ];
                })->all(),
                'total' => $this->links->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListLinkAsGuest()
    {
        $this->getJson('/links')
            ->assertSuccessful()
            ->assertJson([
                'data' => $this->links->map(function (Link $link):array {
                    return [
                        'id'    => $link->id,
                        'title'  => $link->title,
                        'url'  => $link->url,
                        'op_id' => $link->op_id,
                        'op' => [
                            'id'     => $link->op->id,
                            'handle' => $link->op->handle,
                        ],
                        'ld_id' => $link->ld_id,
                        'linkdomain' => [
                            'id'     => $link->linkdomain->id,
                            'domain' => $link->linkdomain->domain,
                            'text' => $link->linkdomain->text,
                        ],
                    ];
                })->all(),
                'total' => $this->links->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
