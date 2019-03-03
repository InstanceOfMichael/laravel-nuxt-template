<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Answer' => 'App\Policies\AnswerPolicy',
        'App\Claim' => 'App\Policies\ClaimPolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Question' => 'App\Policies\QuestionPolicy',
        'App\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
