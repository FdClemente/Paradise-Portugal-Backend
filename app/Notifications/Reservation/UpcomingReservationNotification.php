<?php

namespace App\Notifications\Reservation;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;

class UpcomingReservationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reservation $reservation)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'expo'];
    }

    public function toMail($notifiable): MailMessage
    {
        \App::setLocale($notifiable->language);

        $propertyName = $this->reservation->house->name ?? 'your accommodation';
        $checkinDate = $this->reservation->check_in_date->format('F j, Y');
        $checkinHour = $this->reservation->house->details->check_in_time->format('h:i a') ?? '3:00 PM';
        $address = $this->reservation->house->address_complete;

        return (new MailMessage)
            ->subject(__('notification.reminder.email.subject', ['property' => $propertyName]))
            ->greeting(__('notification.reminder.email.greeting', ['name' => $notifiable->name]))
            ->line(__('notification.reminder.email.intro', ['property' => "**{$propertyName}**"]))
            ->line(__('notification.reminder.email.details'))
            ->line(__('notification.reminder.email.checkin_date', ['date' => $checkinDate]))
            ->line(__('notification.reminder.email.checkin_time', ['time' => $checkinHour]))
            ->line(__('notification.reminder.email.address', ['address' => $address]))
            ->line(__('notification.reminder.email.footer'))
            ->salutation(__('notification.reminder.email.salutation'));
    }

    public function toExpo(User $notifiable): ExpoMessage
    {
        \App::setLocale($notifiable->language);

        return ExpoMessage::create(__('notification.reminder.title'))
            ->body(__('notification.reminder.body'))
            ->badge(1)
            ->data(['url' => '/Reservation/Cancellation/Status?reservation?'.$this->reservation->id])
            ->playSound();
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
