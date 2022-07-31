<?php

namespace App\Mail\Security;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationSuccessfulMail extends Mailable
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
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.security.account-verification-successful-mail', [
            'user' => $this->user,
        ])
            ->from('accounts@patrimag.tk', 'Équipe des comptes Patri-Mag')
            ->subject('Merci d\'avoir activé votre compte');
    }
}
