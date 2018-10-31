<?php

namespace Tests\Browser\Components;

use App\User;
use Facebook\WebDriver\Exception\ElementNotVisibleException;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class Navbar extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return 'nav.navbar';
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

    public function assertIsUser(Browser $browser, User $user)
    {
        $browser->waitFor('.is-logged-in');
        $browser->assertSee($user->name);
    }

    public function assertIsSignedOut(Browser $browser)
    {
        $browser->assertNotPresent('@logout');
        $browser->assertNotPresent('.is-logged-in');
    }

    public function signout (Browser $browser) {
        if (!$btn = $browser->element('@logout')) {
            $browser->openUserMenu();
        }
        $browser->click('@logout');
        $browser->waitUntilMissing('@logout');
        $browser->waitUntilMissing('.is-logged-in');
    }

    public function clickLogin (Browser $browser) {
        $browser->click('@nav-link-login');
    }

    public function clickSettings (Browser $browser) {
        $browser->click('@nav-link-settings');
    }

    public function openUserMenu (Browser $browser) {
        if (!($btn = $browser->element('@opened-user-menu'))) {
            $browser->click('@toggle-dropdown-menu');
            $browser->waitFor('@opened-user-menu');
        }
    }

    public function open (Browser $browser) {
        if (is_null($browser->element('@opened-hamburger'))) {
            if ($btn = $browser->element('@hamburger')) {
                $btn->click();
                $browser->waitFor('@opened-hamburger');
            }
        }
    }

    public function close (Browser $browser) {
        if ($browser->element('@opened-hamburger')) {
            if ($btn = $browser->element('@hamburger')) {
                $btn->click();
                $browser->waitUntilMissing('@opened-hamburger');
            }
        }
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => 'nav.navbar',
            '@hamburger' => 'button.navbar-toggler',
            '@logout' => '.is-logged-in.show .dropdown-item[dusk="logout"]',
            '@toggle-dropdown-menu' => '.is-logged-in a.dropdown-toggle[role=button]',
            '@opened-user-menu' => '.is-logged-in.show',
            '@opened-hamburger' => '.navbar-collapse.collapse.show',
            '@nav-link-login' => '.nav-link.nav-link-login',
            '@nav-link-register' => '.nav-link.nav-link-register',
            '@nav-link-settings' => '.dropdown-item.dropdown-link-settings',
        ];
    }
}
