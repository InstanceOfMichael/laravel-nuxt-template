<?php

namespace Tests\Browser\Spec\Settings;

use App\User;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Components\ResendEmailVerifyAlert;
use Tests\Browser\Components\SettingsCard;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\Settings\EmailPage;
use Tests\Browser\Pages\Settings\ProfilePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EmailTest extends DuskTestCase
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
                    $card->clickEmailTab();
                })
                ->on(new EmailPage)
                ->assertProfileInformation($this->user)
                ->with(new ResendEmailVerifyAlert, function (Browser $alert) {
                    $alert->assertVerifyEmailWasNotResent();
                    $alert->clickResendVerificationEmail();
                    $alert->assertVerifyEmailWasResent();
                })
                ->on(new EmailPage)
                ->updateProfileInformation([
                    'email' => $this->user->email.'0',
                ])
                ->refresh()
                ->on(new EmailPage)
                ->assertValue('@input_email', $this->user->email.'0');

            $freshUser = $this->user->fresh();

            $this->assertEquals($freshUser->email, $this->user->email.'0');

            $browser->assertProfileInformation($freshUser);
        });
    }
}
