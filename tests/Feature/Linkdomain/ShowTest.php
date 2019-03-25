<?php

namespace Tests\Feature\Linkdomain;

use App\User;
use App\Link;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Link */
    protected $link;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->link = factory(Link::class)->create([
            'op_id' => $this->user->id,
        ]);
    }

    public function testShowLinkAsUser()
    {
        $link = $this->link;
        $this->actingAs($this->user)
            ->getJson('/linkdomains/'.$this->link->linkdomain->id)
            ->assertSuccessful()
            ->assertJson([
                'id'     => $link->linkdomain->id,
                'domain' => $link->linkdomain->domain,
                'text' => $link->linkdomain->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testShowLinkAsGuest()
    {
        $link = $this->link;
        $this->getJson('/linkdomains/'.$this->link->linkdomain->id)
            ->assertSuccessful()
            ->assertJson([
                'id'     => $link->linkdomain->id,
                'domain' => $link->linkdomain->domain,
                'text' => $link->linkdomain->text,
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
