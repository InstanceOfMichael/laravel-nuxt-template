<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\Alert;

class PasswordEmailPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/password/reset';
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
    public function submitPasswordReset(Browser $browser, User $user)
    {
        $browser->type('@input_email', $user->email);
        $browser->click('@button_submit');
        $browser->waitUntilLoaded();
        $browser->whenAvailable(new Alert(), function (Browser $alert) {
            $alert->assertSuccess();
        });
        // $browser->waitForLocation($this->url());
        // $browser->type('@input_email', $user->email);
        // $browser->type('@input_password', 'secret');
        // $browser->click('@button_sign_in');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '[dusk="password_email_page"]',
            '@input_email' => 'input[name=email]',
            '@button_submit' => 'button[name="reset_pw"]',
        ];
    }
}
