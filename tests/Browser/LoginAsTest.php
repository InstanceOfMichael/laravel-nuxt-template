<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\RegisterPage;

class LoginAsTest extends DuskTestCase
{
    /**
     * @var $user User
     */
    protected $user;

    public function setUp () {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user);
                $browser->visit(new HomePage)
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->signout();
                    $navbar->assertIsSignedOut();
                    $navbar->close();
                })
                ->logout()
                ->on(new LoginPage)
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsSignedOut();
                    $navbar->close();
                })
                ;
        });
    }
}
