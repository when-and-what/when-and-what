<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PocketCastsAuthenticationFailed extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pocket Casts connection needs attention')
            ->line('Pocket Casts rejected the access token we have for your account, so your podcast history is no longer being synced.')
            ->line('Reconnect Pocket Casts with a new token to resume syncing.')
            ->action('Reconnect Pocket Casts', route('accounts.edit', 'pocketcasts'));
    }
}
