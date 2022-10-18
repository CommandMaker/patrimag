<?php

namespace App\Listeners;

use App\Events\ArticleCreatedEvent;
use App\Mail\Newsletter\NewsletterArticleCreatedMail;
use App\Models\User;
use Mail;

class ArticleCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  ArticleCreatedEvent  $event
     * @return void
     */
    public function handle(ArticleCreatedEvent $event)
    {
        $usersToSend = User::where('is_subscribed_newsletter', true)->get();

        foreach ($usersToSend as $user) {
            Mail::to($user)->send(new NewsletterArticleCreatedMail($user, $event->getArticle()));
        }
    }
}
