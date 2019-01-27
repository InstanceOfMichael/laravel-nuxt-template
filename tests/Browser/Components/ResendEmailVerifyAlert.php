<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class ResendEmailVerifyAlert extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '[dusk="resent_email_verify_alert"]';
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
     * @param  Browser  $browser
     * @return void
     */
    public function assertVerifyEmailWasResent(Browser $browser)
    {
        $browser->assertVisible('.alert-is-resent');
        $browser->assertMissing('@prompt_verify_email_resend');
    }

    /**
     * @param  Browser  $browser
     * @return void
     */
    public function assertVerifyEmailWasNotResent(Browser $browser)
    {
        $browser->assertMissing('.alert-is-resent');
        $browser->assertVisible('@prompt_verify_email_resend');
    }

    /**
     * @param  Browser  $browser
     * @return void
     */
    public function clickResendVerificationEmail(Browser $browser)
    {
        $browser->click('[dusk=prompt_verify_email_resend] [dusk=resend]');
        $browser->waitUntilMissing('[dusk=prompt_verify_email_resend] .busy');
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
