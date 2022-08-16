<?php

namespace App\Mail\UserActions;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserBannedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        protected User $user
    ) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->view('mails.user-actions.user-banned', [
            'user' => $this->user,
        ])
            ->subject('Bannissement de votre compte Patri-Mag')
            ->from('accounts@patrimag.tk', 'Équipe des comptes Patri-Mag');
    }
}
