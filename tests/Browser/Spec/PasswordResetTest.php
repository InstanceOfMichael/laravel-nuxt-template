<?php

namespace Tests\Browser\Spec;

use App\User;
use Carbon\Carbon;
use DB;
use Tests\Browser\Components\Navbar;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\PasswordEmailPage;
use Tests\Browser\Pages\PasswordResetAfterEmailPage;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PasswordResetTest extends DuskTestCase
{
    /**
     * @var $user User
     */
    protected $user;
    protected $startOfTest;
    protected $passwordReset;
    protected $logId;
    protected $newPassword;

    public function setUp () {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->startOfTest = Carbon::now();
        // remove characters that make regex weird
        $this->logId = str_replace(['\\', '.'], '_', __CLASS__.'_'.uniqid('', true));
        $this->newPassword = 'secretsecretsecretsecret1234567890!';
    }
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testAcceptance()
    {
        $this->passwordReset = null;

        logger()->debug('START_'.$this->logId);
        $this->browse(function (Browser $browser) {
            $browser->visit(new LoginPage)
                ->click('@forgot_password')
                ->on(new PasswordEmailPage)
                ->submitPasswordReset($this->user)
                ;

            $browser->waitUsing(null, null, function () {
                $this->passwordReset = DB::table('password_resets')
                    ->where('created_at', '>', $this->startOfTest)
                    ->where('email', $this->user->email)
                    ->first();
                return $this->passwordReset !== null;
            });
            logger()->debug('END_'.$this->logId);

            $reset_path = $this->getUrlFromMailFromLog();

            $browser->visit($reset_path)
                ->on(new PasswordResetAfterEmailPage($reset_path))
                ->submitNewPassword($this->user, $this->newPassword)
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsSignedOut();
                    $navbar->clickLogin();
                    $navbar->close();
                })
                ->on(new LoginPage())
                ->signInAccount($this->user, $this->newPassword)
                ->on(new HomePage())
                ->with(new Navbar(), function ($navbar) {
                    $navbar->open();
                    $navbar->assertIsUser($this->user);
                    $navbar->signout();
                    $navbar->assertIsSignedOut();
                })
                ;
        });
    }

    protected function getUrlFromMailFromLog (): string {
        $result = shell_exec($ag_cmd = 'ag "_'.$this->logId.'|Reset Password:\s*http.*/password/reset/" storage/logs/laravel.log');

        $result = explode('START_'.$this->logId, $result);
        $this->assertEquals(2, count($result));
        $result = $result[1];
        $result = explode('END_'.$this->logId, $result);
        $this->assertEquals(2, count($result));
        $result = trim($result[0]);
        $result = explode("\n", $result);
        $result = trim($result[0]);
        $result = strstr($result, 'http');

        // @example: http://ln.d:3000/password/reset/d829cb7be164c835c3764e3c74935d9ed90eb1b16ab135755776be380668d425?email=yprice%40example.net

        return $result;
    }
}
