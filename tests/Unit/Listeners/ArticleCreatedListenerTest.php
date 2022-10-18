<?php

namespace Tests\Unit\Listeners;

use App\Events\ArticleCreatedEvent;
use App\Listeners\ArticleCreatedListener;
use App\Mail\Newsletter\NewsletterArticleCreatedMail;
use App\Models\Article;
use App\Models\User;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mail;
use Tests\TestCase;

class ArticleCreatedListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testIfMailIsSentOneTimes(): void
    {
        Mail::fake();
        Event::fake();

        $user = User::factory()->create([
            'is_subscribed_newsletter' => true,
        ]);
        $article = Article::factory()->create();

        $event = new ArticleCreatedEvent($article);
        $listener = new ArticleCreatedListener();
        $listener->handle($event);

        Mail::assertSent(NewsletterArticleCreatedMail::class, 1);
    }
}
