<?php

namespace Tests;

use App\User;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser as BaseBrowser;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;

class DuskBrowser extends BaseBrowser
{
    /**
     * The base URL for all api URLs.
     *
     * @var string
     */
    public static $apiBaseUrl;

    public function waitUntilLoaded () {
        $this->waitUntilMissing('i.fa.fa-spinner.fa-spin, .btn-loading');
    }

    public function assertNotPresent (string $selector) {
        // @todo implement not present
        $this->assertMissing($selector);
    }

    /**
     * Log into the application using a given user ID or email.
     *
     * Modified to recognize difference between static path
     * and api path
     *
     * @param  object|string  $userId
     * @param  string         $guard
     * @return $this
     */
    public function loginAs($userId, $guard = null)
    {
        $this->visit(new LoginPage);
        $this->signInAccount($userId instanceOf User ? $userId : User::findOrFail($userId));
        $this->on(new HomePage());
        return $this;
    }

    /**
     * Log out of the application.
     *
     * Modified to recognize difference between static path
     * and api path
     *
     * @param  string  $guard
     * @return $this
     */
    public function logout($guard = null)
    {
        $this->waitUntil('typeof window.$nuxt !== \'undefined\'');
        $this->script(
            'function afterLogout () { window.$nuxt.$router.push({ name: \'login\' }) } '
            .'window.$nuxt.$store.dispatch(\'auth/logout\').then(afterLogout, afterLogout); '
            );

        $this->waitForLocation('/login');

        return $this;
    }
}
