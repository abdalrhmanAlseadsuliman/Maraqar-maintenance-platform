<?php

// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

Route::post('/save-subscription', function (Request $request) {
    $user = auth()->user();

    if ($user) {
        // حفظ الاشتراك في قاعدة البيانات باستخدام بيانات الاشتراك
        $user->updatePushSubscription(
            $request->input('endpoint'),
            $request->input('keys.p256dh'),
            $request->input('keys.auth')
        );

        // استخدام Guzzle لإرسال الطلب باستخدام Push Subscription
        $client = new Client();

        // عنوان الخادم الذي يستقبل الاشتراك (مثلاً واجهة API خارجية)
        $endpoint = '/save-subscription';

        try {
            // إرسال طلب HTTP باستخدام Guzzle
            $response = $client->post($endpoint, [
                'json' => [
                    'endpoint' => $request->input('endpoint'),
                    'keys' => [
                        'p256dh' => $request->input('keys.p256dh'),
                        'auth' => $request->input('keys.auth')
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-CSRF-TOKEN' => $request->header('X-CSRF-TOKEN')  // الحصول على CSRF token من الهيدر
                ]
            ]);

            // التحقق من استجابة السيرفر
            if ($response->getStatusCode() == 200) {
                return response()->json(['success' => true]);
            }

        } catch (\Exception $e) {
            // في حالة حدوث خطأ أثناء إرسال الطلب
            return response()->json(['error' => 'Failed to save subscription'], 500);
        }
    }

    // في حال لم يكن هناك مستخدم مسجل الدخول
    return response()->json(['error' => 'User not authenticated'], 401);
});
