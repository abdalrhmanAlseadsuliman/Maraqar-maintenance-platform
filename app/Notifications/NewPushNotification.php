<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;


class NewPushNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected string $title;
    protected string $body;
    protected string $url;

    public function __construct(string $title = 'test test test', string $body = 'تم استلام طلب test! اضغط لعرض التفاصيل.', string $url = '/maintenance/requests')
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }



    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->action('عرض', $this->url)
            ->icon(asset('white-logo.webp'))
            ->badge(asset('white-logo.webp'))

            ->data([
                'id' => $notification->id,
                'url' => url($this->url),
            ]);
    }
}


// public function toWebPush($notifiable, $notification)
    // {
    //     return (new WebPushMessage)
    //         ->title($this->title)
    //         ->body($this->body)
    //         ->action('عرض', $this->url)
    //         ->icon(asset('white-logo.webp'))
    //         ->badge(asset('white-logo.webp'))
    //         ->data(['id' => $notification->id]);
    // }

    // public function toWebPush($notifiable, $notification)
    // {
    //     return (new WebPushMessage)
    //         ->title($this->title)
    //         ->body($this->body)
    //         ->action('عرض', 'open_url') // يمكنك تخصيص action name لو أردت
    //         ->icon(asset('white-logo.webp'))
    //         ->badge(asset('white-logo.webp'))
    //         ->data([
    //             'id' => $notification->id,
    //             'url' => $this->url, // ✅ أضف هذا السطر
    //         ]);
    // }
