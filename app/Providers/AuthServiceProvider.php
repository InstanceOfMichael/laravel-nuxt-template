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
        'App\Allowedquestionside' => 'App\Policies\AllowedquestionsidePolicy',
        'App\Answer' => 'App\Policies\AnswerPolicy',
        'App\Claim' => 'App\Policies\ClaimPolicy',
        'App\Claimrelation' => 'App\Policies\ClaimrelationPolicy',
        'App\Claimside' => 'App\Policies\ClaimsidePolicy',
        'App\Comment' => 'App\Policies\CommentPolicy',
        'App\Group' => 'App\Policies\GroupPolicy',
        'App\Groupmembership' => 'App\Policies\GroupmembershipPolicy',
        'App\Groupsubscription' => 'App\Policies\GroupsubscriptionPolicy',
        'App\Link' => 'App\Policies\LinkPolicy',
        'App\Linkdomain' => 'App\Policies\LinkdomainPolicy',
        'App\Question' => 'App\Policies\QuestionPolicy',
        'App\Side' => 'App\Policies\SidePolicy',
        'App\Topic' => 'App\Policies\TopicPolicy',
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
