<?php

namespace App\Notifications\Reservation;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;

class NewReservationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Reservation $reservation)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        \App::setLocale($notifiable->language);

        $propertyName = $this->reservation->house->name ?? 'your accommodation';
        $checkinDate = $this->reservation->check_in_date->format('F j, Y');
        $checkinHour = $this->reservation->house->details->check_in_time->format('h:i a') ?? '3:00 PM';
        $address = $this->reservation->house->address_complete;

        return (new MailMessage)
            ->subject(__('notification.booking_created.email.subject', ['property' => $propertyName]))
            ->greeting(__('notification.booking_created.email.greeting', ['name' => $notifiable->name]))
            ->line(__('notification.booking_created.email.intro', ['property' => "**{$propertyName}**"]))
            ->line(__('notification.booking_created.email.details'))
            ->line(__('notification.booking_created.email.checkin_date', ['date' => $checkinDate]))
            ->line(__('notification.booking_created.email.checkin_time', ['time' => $checkinHour]))
            ->line(__('notification.booking_created.email.address', ['address' => $address]))
            ->line(__('notification.booking_created.email.footer'))
            ->salutation(__('notification.booking_created.email.salutation'));
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
