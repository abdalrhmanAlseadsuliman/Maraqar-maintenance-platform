<?php
namespace App\Observers;

use App\Models\MaintenanceRequests;
use App\Models\User;
use App\Notifications\NewMaintenanceRequestNotification;
use App\Notifications\NewPushNotification;

class MaintenanceRequestObserver
{

    public function created(MaintenanceRequests $request)
    {

        $admins = User::where('role', 'admin')->get();

        // foreach ($admins as $admin) {
        //     $admin->notify(new NewPushNotification());
        // }

        foreach ($admins as $admin) {
            $admin->notify(new NewMaintenanceRequestNotification(
                $request,
                'تم تعديل طلب صيانة',
                'تم تعديل حالة طلب الصيانة رقم: ' . $request->id
            ));
            $admin->notify(new NewPushNotification(
                'تم تعديل طلب صيانة',
                'تم تعديل حالة الطلب، اضغط لعرض التفاصيل.',
                '/maintenance/requests/' . $request->id
            ));
        }
    }
    public function edited(MaintenanceRequests $request)
    {

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewPushNotification());
        }
        foreach ($admins as $admin) {
            $admin->notify(new NewMaintenanceRequestNotification($request));
        }
    }
}
