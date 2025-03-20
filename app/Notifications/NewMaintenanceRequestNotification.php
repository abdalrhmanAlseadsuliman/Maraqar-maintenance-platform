<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\MaintenanceRequests;

class NewMaintenanceRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $request;

    public function __construct(MaintenanceRequests $request)
    {
        $this->request = $request;
    }

    /**
     * 
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * 
     */
    public function toDatabase($notifiable)
    {
        return FilamentNotification::make()
            ->title('طلب صيانة جديد')
            ->body("تم استلام طلب صيانة جديد برقم: " . $this->request->id)
           
            ->getDatabaseMessage();
    }
}
