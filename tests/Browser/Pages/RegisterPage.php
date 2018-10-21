<?php

namespace Tests\Browser\Pages;

use App\User;
use Laravel\Dusk\Browser;

class RegisterPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
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
    public function registerAccount(Browser $browser, User $user)
    {
        $browser->waitForLocation($this->url());
        $browser->type('@input_name', $user->name);
        $browser->type('@input_email', $user->email);
        $browser->type('@input_password', $pw = 'secret');
        $browser->type('@input_password_confirmation', $pw);
        $browser->click('@button_register');
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
            '@element' => '[dusk="register_page"]',
            '@input_name' => 'input[name=name]',
            '@input_email' => 'input[name=email]',
            '@input_password' => 'input[name=password]',
            '@input_password_confirmation' => 'input[name=password_confirmation]',
            '@button_register' => 'button[name="register"]',
        ];
    }
}
