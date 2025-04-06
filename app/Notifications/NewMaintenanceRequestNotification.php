<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\MaintenanceRequests;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMaintenanceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;

    public function __construct(MaintenanceRequests $request)
    {
        $this->request = $request;
    }

    // نرسل عبر database + broadcast معًا
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // إشعار يظهر في لوحة التحكم
    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title('طلب صيانة جديد')
            ->body("تم استلام طلب صيانة جديد برقم: " . $this->request->id)
            ->getDatabaseMessage();
    }

    // إشعار حي (live real-time)
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return FilamentNotification::make()
            ->title('طلب صيانة جديد')
            ->body("تم استلام طلب صيانة جديد برقم: " . $this->request->id)
            ->getBroadcastMessage();
    }
}
