<?php

namespace App\Mail\Security;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordUpdatedMail extends Mailable
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
        return $this->view('mails.security.password-updated-mail', [
            'user' => $this->user,
        ])
            ->subject('Alerte de sécurité ! Mot de passe modifié')
            ->from('accounts@patrimag.tk', 'Équipe des comptes Patri-Mag');
    }
}
