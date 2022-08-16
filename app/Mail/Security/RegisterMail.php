<?php

namespace App\Mail\Security;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable
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
    public function build()
    {
        return $this->view('mails.security.register-mail', [
            'user' => $this->user,
        ])
            ->subject('Activez votre compte Patri-Mag')
            ->from('accounts@patrimag.tk', 'Équipe des comptes Patri-Mag');
    }
}
