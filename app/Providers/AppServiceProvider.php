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
        // if you wanted to use ints instead of strings for context_type
        // Relation::morphMap([
        //     2 => \App\User::class,
        //     3 => \App\Comment::class,
        // ]);
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
        \Illuminate\Database\Query\Builder::mixin(new \App\Mixins\QueryBuilder);
    }
}
