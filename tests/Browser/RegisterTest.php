<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\WelcomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\RegisterPage;

class RegisterTest extends DuskTestCase
{
    /**
     * @var $user User
     */
    protected $user;

    public function setUp () {
        parent::setUp();

        $this->user = factory(User::class)->make();
    }
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->assertEquals(0, User::query()
                ->where('email', $this->user->email)
                ->count());

        $this->browse(function (Browser $browser) {
            $browser
                ->visit(new RegisterPage())
                ->registerAccount($this->user)
                ;

            $this->assertEquals(1, User::query()
                    ->where('email', $this->user->email)
                    ->count());

            $createdUser = User::query()
                    ->where('email', $this->user->email)
                    ->firstOrFail();

            $this->assertEquals($this->user->email, $createdUser->email);
            $this->assertEquals($this->user->name, $createdUser->name);

            $browser->on(new HomePage())
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->signout();
                    $navbar->assertIsSignedOut();
                    $navbar->clickLogin();
                    $navbar->close();
                })
                ->on(new LoginPage())
                ->signInAccount($this->user)
                ->on(new HomePage())
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->signout();
                    $navbar->assertIsSignedOut();
                })
                ->on(new LoginPage())
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsSignedOut();
                })
                ;
        });
    }
}
