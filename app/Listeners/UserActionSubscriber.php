<?php

namespace App\Listeners;

use App\Events\UserBannedEvent;
use App\Events\UserSuspendedEvent;
use App\Events\UserUnbannedEvent;
use App\Events\UserUnsuspendedEvent;
use App\Mail\UserActions\UserBannedMail;
use App\Mail\UserActions\UserSuspendedMail;
use App\Mail\UserActions\UserUnbannedMail;
use App\Mail\UserActions\UserUnsuspendedMail;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;

class UserActionSubscriber
{
    public function userBanned(UserBannedEvent $event): void
    {
        Mail::to($event->getUser())->send(new UserBannedMail($event->getUser()));
    }

    public function userUnbanned(UserUnbannedEvent $event): void
    {
        Mail::to($event->getUser())->send(new UserUnbannedMail($event->getUser()));
    }

    public function userSuspended(UserSuspendedEvent $event): void
    {
        Mail::to($event->getUser())->send(new UserSuspendedMail($event->getUser()));
    }

    public function userUnsuspended(UserUnsuspendedEvent $event): void
    {
        Mail::to($event->getUser())->send(new UserUnsuspendedMail($event->getUser()));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     * @return array
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            UserBannedEvent::class => 'userBanned',
            UserUnbannedEvent::class => 'userUnbanned',
            UserSuspendedEvent::class => 'userSuspended',
            UserUnsuspendedEvent::class => 'userUnsuspended',
        ];
    }
}
