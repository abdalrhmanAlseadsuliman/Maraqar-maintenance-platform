<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\MaintenanceRequests;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMaintenanceRequestNotification extends Notification
{
    protected MaintenanceRequests $request;
    protected string $title;
    protected string $body;

    public function __construct(MaintenanceRequests $request, string $title = 'طلب صيانة جديد',  $body = null)
    {
        $this->request = $request;
        $this->title = $title;
        $this->body = $body ?? "تم استلام طلب صيانة جديد برقم: " . $request->id;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable): array
    {
        return FilamentNotification::make()
            ->title($this->title)
            ->body($this->body)
            ->getDatabaseMessage();
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return FilamentNotification::make()
            ->title($this->title)
            ->body($this->body)
            ->getBroadcastMessage();
    }
}
