<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoMessage;

class MarketingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $title, public string $body = '', public ?string $url = null)
    {
    }

    public function via($notifiable): array
    {
        if ($notifiable->allow_marketing_notifications)
            return ['expo'];

        return [];
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
            ->when($this->url, fn($message) => $message->data(['url' => $this->url]))
            ->playSound();
    }
}
