<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\Alert;

class PasswordResetAfterEmailPage extends Page
{
    protected $token;

    public function __construct (string $token) {
        // extract from URL, if URL
        if (starts_with($token, 'http')) {
            $token = explode('/', $token)[5];
            $token = explode('?', $token)[0];
        }
        // extract from URL, if URL
        if (starts_with($token, '/password/reset/')) {
            $token = explode('/', $token)[3];
            $token = explode('?', $token)[0];
        }
        $this->token = $token;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return "/password/reset/{$this->token}";
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitFor('@element');
        $browser->assertPathIs($this->url());
        $browser->assertVisible('@element');
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function submitNewPassword(Browser $browser, User $user, string $newPassword)
    {
        $browser->assertValue('@input_email', $user->email);
        $browser->type('@input_password', $newPassword);
        $browser->type('@input_password_confirmation', $newPassword);
        $browser->click('@button_submit');
        $browser->waitUntilLoaded();
        $browser->whenAvailable(new Alert(), function (Browser $alert) {
            $alert->assertSuccess();
        });
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '[dusk="password_reset_page"]',
            '@input_email' => 'input[name=email]',
            '@input_password' => 'input[name=password]',
            '@input_password_confirmation' => 'input[name=password_confirmation]',
            '@button_submit' => 'button[name="set_password"]',
        ];
    }
}
