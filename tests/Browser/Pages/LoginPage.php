<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;

class LoginPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/login';
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
    public function signInAccount(Browser $browser, User $user)
    {
        $browser->waitForLocation($this->url());
        $browser->type('@input_email', $user->email);
        $browser->type('@input_password', 'secret');
        $browser->click('@button_sign_in');
        $browser->waitUntilLoaded();
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '[dusk="login_page"]',
            '@input_email' => 'input[name=email]',
            '@input_password' => 'input[name=password]',
            '@button_sign_in' => 'button[name="sign_in"]',
        ];
    }
}
