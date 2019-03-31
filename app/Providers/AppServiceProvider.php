<?php

namespace App\Providers;

use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningUnitTests()) {
            Schema::defaultStringLength(191);
        }
        Relation::morphMap([
            2 => \App\User::class,
            3 => \App\Comment::class,
            4 => \App\Question::class,
            5 => \App\Claim::class,
            6 => \App\Answer::class,
            7 => \App\Claimrelation::class,
            8 => \App\Link::class,
            9 => \App\Linkdomain::class,
            10 => \App\Side::class,
            11 => \App\Allowedquestionside::class,
            12 => \App\Claimside::class,
            13 => \App\Group::class,
            13 => \App\Groupsmembership::class,
            13 => \App\Groupsubscription::class,
        ]);
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('U');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
            \Illuminate\Foundation\Testing\TestResponse::mixin(new \Tests\TestResponseMacros);
        }
    }
}
