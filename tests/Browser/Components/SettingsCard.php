<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class SettingsCard extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '@element';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function clickPasswordTab(Browser $browser)
    {
        $browser->click('@password_link');
        $browser->waitUntilLoaded();
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '[dusk="settings_frame"] .card.settings-card',
            '@password_link' => '[dusk="settings_password_link"]',
            '@profile_link' => '[dusk="settings_profile_link"]',
        ];
    }
}
