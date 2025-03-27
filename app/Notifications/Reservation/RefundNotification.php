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

        return (new MailMessage())
            ->subject(__('notification.refund.title', locale: $notifiable->language))
            ->greeting(__('notification.refund.greeting', locale: $notifiable->language))
            ->line(__('notification.refund.body', locale: $notifiable->language))
            ->line(__('notification.refund.signature', locale: $notifiable->language));
    }

    public function toExpo(User $notifiable): ExpoMessage
    {
        return ExpoMessage::create(__('notification.refund.title', locale: $notifiable->language))
            ->body(__('notification.refund.body', locale: $notifiable->language))
            ->badge(1)
            ->data(['url' => '/Reservation/Cancellation/Status?reservation?'.$this->reservation->id])
            ->playSound();
    }
}
