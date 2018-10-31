<?php

namespace Tests\Browser\Spec\Settings;

use App\User;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\Settings\ProfilePage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends DuskTestCase
{
    /**
     * @var $user User
     */
    protected $user;

    public function setUp () {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->newPassword = 'secretsecretsecretsecret1234567890!';
    }

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->on(new HomePage)
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->openUserMenu();
                    $navbar->clickSettings();
                })
                ->on(new ProfilePage)
                ->assertProfileInformation($this->user)
                ->updateProfileInformation([
                    'name' => $this->user->name.'0',
                    'email' => $this->user->email.'0',
                ])
                ->refresh()
                // ->visit(new ProfilePage)
                ->on(new ProfilePage)
                ->assertValue('@input_name', $this->user->name.'0')
                ->assertValue('@input_email', $this->user->email.'0');

            $freshUser = $this->user->fresh();

            $this->assertEquals($freshUser->name, $this->user->name.'0');
            $this->assertEquals($freshUser->email, $this->user->email.'0');

            $browser->assertProfileInformation($freshUser);
        });
    }
}
