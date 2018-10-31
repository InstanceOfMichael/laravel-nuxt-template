<?php

namespace Tests\Browser\Pages\Settings;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\Alert;
use Tests\Browser\Pages\Page;

class PasswordPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return "/settings/password";
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
            '@element' => '[dusk="settings_password_page"]',
            '@input_email' => 'input[name=email]',
            '@input_password' => 'input[name=password]',
            '@input_password_confirmation' => 'input[name=password_confirmation]',
            '@button_submit' => 'button[name="set_password"]',
        ];
    }
}
