<?php

namespace Tests\Browser\Spec;

use Tests\Browser\Pages\WelcomePage;
use Tests\Browser\Components\Navbar;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class WelcomePageTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new WelcomePage);
            $browser->assertSee('lndebate');
            $browser->assertNotPresent((new Navbar)->selector());
        });
    }
}
