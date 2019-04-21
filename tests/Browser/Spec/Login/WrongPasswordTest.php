<?php

namespace Tests\Browser\Spec\Login;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\RegisterPage;

class WrongPasswordTest extends DuskTestCase
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
        $this->markTestSkipped('todo');
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage);
            $browser->signInAccount($this->user);
            // $browser->screenshot(__LINE__);
        });
    }
}
