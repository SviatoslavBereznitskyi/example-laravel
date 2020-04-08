<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FirstSubscriptionEmail extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param  mixed $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('email.firstSubscriptionEmail.subject'))
            ->line(trans('email.firstSubscriptionEmail.congratulations'))
            ->action(
                trans('email.firstSubscriptionEmail.actionText'),
                config('web-client.routes.auth.login')
            );
    }
}
