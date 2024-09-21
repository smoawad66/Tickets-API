<?php

namespace App\Providers;

use App\Exceptions\ApiExceptionsHandler;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            ApiExceptionsHandler::class,
            fn() => new ApiExceptionsHandler
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Ticket::class, \App\Policies\V1\TicketPolicy::class);
        Gate::policy(User::class, \App\Policies\V1\UserPolicy::class);
    }
}
