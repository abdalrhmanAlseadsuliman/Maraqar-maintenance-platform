<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\MaintenanceRequests;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Filament\Notifications\Actions\Action;

// class NewMaintenanceRequestNotification extends Notification
// {
//     protected MaintenanceRequests $request;
//     protected string $title;
//     protected string $body;

//     public function __construct(MaintenanceRequests $request, string $title = 'طلب صيانة جديد',  $body = null)
//     {
//         $this->request = $request;
//         $this->title = $title;
//         $this->body = $body ?? "تم استلام طلب صيانة جديد برقم: " . $request->id;
//     }

//     public function via($notifiable)
//     {
//         return ['database', 'broadcast'];
//     }

//     public function toDatabase($notifiable): array
//     {
//         return FilamentNotification::make()
//             ->title($this->title)
//             ->body($this->body)
//             ->actions([
//                 Action::make('عرض')
//                     ->button()
//                     ->url(url("/user/maintenance-requests/{$this->request->id}"), shouldOpenInNewTab: false)
//             ])
//             ->getDatabaseMessage();
//     }

//     public function toBroadcast($notifiable): BroadcastMessage
//     {
//         return FilamentNotification::make()
//             ->title($this->title)
//             ->body($this->body)
//             ->actions([
//                 Action::make('عرض')
//                     ->button()
//                     ->label('عرض ')
//                     ->url(url("/user/maintenance-requests/{$this->request->id}"), shouldOpenInNewTab: false)
//             ])
//             ->getBroadcastMessage();
//     }

// }


class NewMaintenanceRequestNotification extends Notification
{
    protected MaintenanceRequests $request;
    protected string $title;
    protected string $body;
    protected string $url;

    public function __construct(MaintenanceRequests $request, string $title = 'طلب صيانة جديد', string $body = null, string $url = '')
    {
        $this->request = $request;
        $this->title = $title;
        $this->body = $body ?? "تم استلام طلب صيانة جديد برقم: " . $request->id;
        $this->url = $url;
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
            ->actions([
                Action::make('عرض')
                    ->button()
                    ->url($this->url, shouldOpenInNewTab: false)
            ])
            ->getDatabaseMessage();
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return FilamentNotification::make()
            ->title($this->title)
            ->body($this->body)
            ->actions([
                Action::make('عرض')
                    ->button()
                    ->label('عرض ')
                    ->url($this->url, shouldOpenInNewTab: false)
            ])
            ->getBroadcastMessage();
    }
}
