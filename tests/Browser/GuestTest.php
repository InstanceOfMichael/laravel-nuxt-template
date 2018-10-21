<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\RegisterPage;
use Tests\Browser\Pages\WelcomePage;

class GuestTest extends DuskTestCase
{
    /**
     * @var function
     */
    protected $not_logged_in;

    public function setUp () {
        parent::setUp();

        $this->not_logged_in = function ($navbar) {
            $navbar->open();
            $navbar->assertIsSignedOut();
            $navbar->close();
        };
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visit('/')
                ->on(new WelcomePage)
                ->assertVisible('@nav-link-login')
                ->assertVisible('@nav-link-register')
                ->visit('/home')
                ->waitForLocation('/login')
                ->on(new LoginPage)
                ->with(new Navbar(), $this->not_logged_in)
                ->visit('/register')
                ->on(new RegisterPage)
                ->with(new Navbar(), $this->not_logged_in)
                ->visit('/login')
                ->on(new LoginPage)
                ->with(new Navbar(), $this->not_logged_in)
                ;
        });
    }
}
