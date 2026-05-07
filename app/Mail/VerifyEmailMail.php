<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vérifiez votre adresse email',
        );
    }

    public function content(): Content
    {
        // Génération de l'URL signée (valable 60 minutes)
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', // Nom de la route définie dans web.php
            now()->addMinutes(60),
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)]
        );

        return new Content(
            view: 'emails.verify-email',
            with: [
                'title' => 'Bienvenue, ' . $this->user->name,
                'url' => $verificationUrl,
                'buttonText' => 'Vérifier mon adresse email',
            ],
        );
    }
}