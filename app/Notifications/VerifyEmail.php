<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{
    use Queueable;

    public function __construct(private readonly string $routeName = 'verification.verify')
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $verificationUrl = URL::temporarySignedRoute(
            $this->routeName,
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('Confirma tu cuenta.')
            ->greeting('¡Hola!')
            ->line('Gracias por Registrarte en "*", tu cuenta ya esta lista solo debes confirmarla')
            ->action('Confirmar tu cuenta', $verificationUrl)
            ->line('Si no fuiste tu, puedes ignorar este email')
            ->salutation('Saludos.');
    }
}
