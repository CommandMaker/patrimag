<?php

namespace App\Mail\Newsletter;

use App\Models\Article;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Parsedown;
use Str;

class NewsletterArticleCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Markdown parser instance to render a little bit of the article inside the mail
     *
     * @var Parsedown
     */
    protected Parsedown $parsedown;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        protected User $user,
        protected Article $article
    ) {
        $this->parsedown = app()->make(Parsedown::class);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Nouvel article sur le Patri-Mag',
            from: new Address('newsletter@patrimag.tk', 'Newsletter Patri-Mag')
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $a = (object) [
            'title' => $this->article->title,
            'content' => $this->parsedown->text(Str::limit($this->article->content, 150, ' [...]')),
        ];

        return new Content(
            view: 'mails.newsletter.new-article',
            with: [
                'user' => $this->user,
                'article' => $a,
            ]
        );
    }
}
