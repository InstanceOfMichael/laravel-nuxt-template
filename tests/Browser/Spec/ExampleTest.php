<?php

namespace Tests\Browser\Spec;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->pause(60000);
            $browser->assertSee('ln');

            // $browser->pause(250);
            // dump($browser->script('return window.location.toString()'));
            // $this->assertTrue(false, 'arrived at end of test');
        });
    }
}
