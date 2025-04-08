<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetCodePasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $code)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        \App::setLocale($notifiable->language);
        return (new MailMessage)
            ->subject(__('notification.password_reset.email.subject'))
            ->greeting(__('notification.password_reset.email.greeting', ['name' => $notifiable->name]))
            ->line(__('notification.password_reset.email.intro'))
            ->line(__('notification.password_reset.email.code', ['code' => $this->code]))
            ->line(__('notification.password_reset.email.expiry'))
            ->salutation(__('notification.password_reset.email.salutation'));
    }


    public function toArray($notifiable): array
    {
        return [];
    }
}
