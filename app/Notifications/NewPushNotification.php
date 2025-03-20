<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NewPushNotification extends Notification
{
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('طلب صيانة جديد')
            ->body('تم استلام طلب جديد! اضغط لعرض التفاصيل.')
            ->action('عرض الطلب', url('/maintenance/requests'))
            ->icon(asset('images/logo.png'))
            ->badge(asset('images/badge.png'))
            ->data(['id' => $notification->id]);
    }
}

