<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\Event' => [
        //     'App\Listeners\EventListener',
        // ],
        'App\Events\ApplicationEdited' => [
            'App\Listeners\SendApplicationEditedNotification',
        ],
        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\AuthFailed',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\AuthAuthenticated',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\AuthLoggedOut',
        ],
        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\PasswordReset',
        ],
        'App\Events\MeritListPartiallyApproveEvent' => [
            'App\Listeners\SendPartiallyApprovedNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
