<?php

namespace Tests\Browser\Pages\Settings;

use App\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\Alert;
use Tests\Browser\Pages\Page;

class EmailPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return "/settings/email";
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->waitFor($this->elements()['@element']);
        $browser->assertPathIs($this->url());
        $browser->assertVisible('@element');
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assertProfileInformation(Browser $browser, User $user)
    {
        $keys = [ 'email' ];
        foreach($keys as $key) {
            $browser->assertValue('@input_'.$key, $user->$key);
        }
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function updateProfileInformation(Browser $browser, array $newInformation)
    {
        $keys = [ 'email' ];
        foreach ($keys as $key) {
            if (array_key_exists($key, $newInformation)) {
                $browser->clearWithBackspace('@input_'.$key);
                $browser->assertValue('@input_'.$key, '');
                $browser->keys('@input_'.$key, $newInformation[$key]);
                $browser->assertValue('@input_'.$key, $newInformation[$key]);
            }
        }

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
            '@element' => '[dusk="settings_email_page"]',
            '@input_email' => 'input[name=email]',
            '@button_submit' => 'button[name="update_profile"]',
        ];
    }
}
