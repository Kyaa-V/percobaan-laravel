<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Experience;
use App\Policies\UserPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ExperiencePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Experience::class, ExperiencePolicy::class);
    }
}
