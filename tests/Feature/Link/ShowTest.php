<?php

namespace Tests\Feature\Link;

use App\User;
use App\Link;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

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
            ->getJson('/links/'.$this->link->id)
            ->assertSuccessful()
            ->assertJson([
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
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }

    public function testShowLinkAsGuest()
    {
        $link = $this->link;
        $this->getJson('/links/'.$this->link->id)
            ->assertSuccessful()
            ->assertJson([
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
            ])
            ->assertDontExposeUserEmails($this->user->email);
    }
}
