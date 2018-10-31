<?php

namespace Tests\Browser\Spec\Settings;

use App\User;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\Settings\PasswordPage;
use Tests\Browser\Pages\Settings\ProfilePage;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Components\SettingsCard;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PasswordTest extends DuskTestCase
{
    /**
     * @var $user User
     */
    protected $user;
    protected $newPassword;

    public function setUp () {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->newPassword = 'secretsecretsecretsecret1234567890!';
    }

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->on(new HomePage)
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->openUserMenu();
                    $navbar->clickSettings();
                })
                ->on(new ProfilePage)
                ->with(new SettingsCard, function (Browser $card) {
                    $card->clickPasswordTab();
                })
                ->on(new PasswordPage)
                ->submitNewPassword($this->user, $this->newPassword)
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->signout();
                    $navbar->assertIsSignedOut();
                    $navbar->close();
                })
                ->on(new LoginPage())
                ->signInAccount($this->user, $this->newPassword)
                ->on(new HomePage())
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->signout();
                    $navbar->assertIsSignedOut();
                })
                ;
        });
    }
}
