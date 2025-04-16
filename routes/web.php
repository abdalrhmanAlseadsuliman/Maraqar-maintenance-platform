<?php
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Log;

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


// Route::middleware(['auth'])->group(function () {
//     Route::post('/import-excel', function () {
//         // سجل رسالة في اللوق لنتأكد أنه تم الوصول للراوت
//         Log::info('وصلنا إلى راوت import-excel');

//         // أرسل استجابة مباشرة لتأكيد الوصول
//         return response()->json(['message' => 'تم الوصول إلى الراوت import-excel بنجاح']);
//     })->name('upload.file');
// });

// 
Route::get('/excel-upload', function () {
    return view('excel-upload'); // تأكد من اسم الملف
});

Route::post('/import-excel', [UserController::class, 'import'])->name('upload.file');



Route::get('/export-excel',[UserController::class,'export']);

Route::post('/test-upload', function () {
    dd('وصل');
});
