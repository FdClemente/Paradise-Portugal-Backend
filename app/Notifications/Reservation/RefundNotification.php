<?php

namespace App\Notifications\Reservation;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;

class RefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reservation $reservation)
    {
    }

    public function via(User $notifiable): array
    {
        return ['expo', 'mail'];
    }

    public function toArray($notifiable): array
    {
        return [];
    }

    public function toMail(object $notifiable): MailMessage
    {
        \App::setLocale($notifiable->language);

        return (new MailMessage())
            ->subject(__('notification.refund.email.title'))
            ->greeting(__('notification.refund.email.greeting', ['name' => $notifiable->name]))
            ->line(__('notification.refund.email.body'))
            ->line(__('notification.refund.email.signature'))
            ->salutation(__('notification.refund.email.salutation'))
            ->line(__('notification.refund.email.footer'));
    }

    public function toExpo(User $notifiable): ExpoMessage
    {
        \App::setLocale($notifiable->language);

        return ExpoMessage::create(__('notification.refund.title'))
            ->body(__('notification.refund.body'))
            ->badge(1)
            ->data(['url' => '/Reservation/Cancellation/Status?reservation?'.$this->reservation->id])
            ->playSound();
    }
}
