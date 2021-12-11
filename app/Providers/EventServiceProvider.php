<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

//* Events
use App\Events\OrderCreatedEvent;
use App\Events\UserRegisteredEvent;
//* Listener
use App\Listeners\OrderCreatedNotificationListener;
use App\Listeners\UserRegisteredNotificationListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        // Custom Event
        OrderCreatedEvent::class => [
            // Custom Listener
            OrderCreatedNotificationListener::class
        ],
        UserRegisteredEvent::class => [
            UserRegisteredNotificationListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
