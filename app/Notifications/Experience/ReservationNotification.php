<?php

namespace App\Notifications\Experience;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationNotification extends Notification implements ShouldQueue
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
        $text = 'The following tickets have been booked:';
        foreach ($this->reservation->tickets as $ticket) {
            $text .= "\n" . $ticket->date . ' - Ticket Type: ' . $ticket->priceDetails->ticket_type;
        }

        return (new MailMessage)
            ->line('A reservation has been made for the experience: ' . $this->reservation->experience->name . '.')
            ->bcc('bookings@paradiseportugal.com')
            ->text($text);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
