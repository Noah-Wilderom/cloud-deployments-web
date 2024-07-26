<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    public function handleUserLogin(Login $event): void
    {
        activity("auth")
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->setEvent($event::class)
            ->log('Gebruiker is zojuist ingelogd');
    }

    public function handleUserLogout(Login $event): void
    {
        activity("auth")
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->setEvent($event::class)
            ->log('Gebruiker is zojuist uitgelogd');
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserLogin',
            Logout::class => 'handleUserLogout',
        ];
    }
}
