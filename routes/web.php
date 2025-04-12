<?php
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\UserConroller;

Route::get('/', function () {
    return view('welcome');
});

// حفظ اشتراك المستخدم في الإشعارات
Route::post('/save-subscription', [PushSubscriptionController::class, 'store'])->middleware('auth');

// إرسال إشعار اختبار يدويًا
Route::get('/send-notification', function () {
    $user = Auth::user(); // إرسال الإشعار للمستخدم الحالي
    if ($user) {
        $user->notify(new \App\Notifications\NewPushNotification());
        return response()->json(['message' => 'Notification sent!']);
    }
    return response()->json(['error' => 'User not authenticated'], 401);
})->middleware('auth');


Route::post('/import-excel',[UserConroller::class,'import'])->name('upload.file');
Route::get('/export-excel',[UserConroller::class,'export']);
