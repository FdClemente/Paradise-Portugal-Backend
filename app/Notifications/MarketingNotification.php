<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;

class MarketingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $title, public string $body = '')
    {
    }

    public function via($notifiable): array
    {
        return ['expo'];
    }

    public function toArray($notifiable): array
    {
        return [];
    }

    public function toExpo($notifiable): ExpoMessage
    {
        return ExpoMessage::create($this->title)
            ->body($this->body)
            ->badge(1)
            //->data(['url' => '/House/10'])
            ->playSound();
    }
}
