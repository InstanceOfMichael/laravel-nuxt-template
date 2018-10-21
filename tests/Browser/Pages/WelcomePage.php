<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class WelcomePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
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
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '[dusk="welcome_page"]',
            '@nav-link-register' => 'a[href="/login"]',
            '@nav-link-login' => 'a[href="/register"]',
        ];
    }
}
