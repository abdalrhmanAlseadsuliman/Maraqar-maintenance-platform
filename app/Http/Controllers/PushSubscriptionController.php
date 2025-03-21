<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user(); // التحقق من تسجيل الدخول
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // حفظ بيانات الاشتراك في قاعدة البيانات
        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'] ?? null,
            $request->keys['auth'] ?? null,
            $request->contentEncoding ?? null
        );

        return response()->json(['success' => true]);
    }
}
