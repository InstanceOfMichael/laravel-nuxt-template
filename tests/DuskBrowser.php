<?php

namespace Tests;

use Laravel\Dusk\Browser as BaseBrowser;

class DuskBrowser extends BaseBrowser
{

    public function waitUntilLoaded () {
        $this->waitUntilMissing('i.fa.fa-spinner.fa-spin, .btn-loading');
    }

    public function assertNotPresent (string $selector) {
        // @todo implement not present
        $this->assertMissing($selector);
    }

}
