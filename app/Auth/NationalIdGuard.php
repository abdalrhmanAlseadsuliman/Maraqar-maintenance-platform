<?php

namespace App\Auth;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class NationalIdGuard implements Guard
{
    use GuardHelpers;

    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function attempt(array $credentials = [], $remember = false)
    {
        // البحث عن المستخدم بناءً على رقم البطاقة الشخصية فقط
        $user = $this->provider->retrieveByCredentials(['national_id' => $credentials['national_id']]);

        if ($user) {
            $this->setUser($user);

            // تسجيل الدخول مع خيار "تذكرني"
            if ($remember) {
                // يمكنك إضافة منطق "تذكرني" هنا إذا أردت
            }

            return true;
        }

        return false;
    }

    public function user()
    {
        return $this->user;
    }

    public function validate(array $credentials = [])
    {
        return $this->provider->retrieveByCredentials(['national_id' => $credentials['national_id']]) !== null;
    }

    public function id()
    {
        if ($this->user()) {
            return $this->user()->getAuthIdentifier();
        }
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function check()
    {
        return ! is_null($this->user());
    }

    public function guest()
    {
        return ! $this->check();
    }
}
