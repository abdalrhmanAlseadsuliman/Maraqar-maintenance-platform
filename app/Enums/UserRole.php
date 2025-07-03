<?php

namespace App\Enums;

use Illuminate\Support\Facades\Auth;

class UserRole
{
    public const CHAIRMAN   = 'CHR';
    public const EXECDIR    = 'EDR';
    public const ADMIN      = 'admin';
    public const ACCOUNTANT = 'ACC';
    public const MAINTTECH  = 'MT';
    public const CLIENT     = 'CLT';

    public static function options(): array
    {
        return [
            'CHR'   => 'رئيس مجلس الإدارة',
            'EDR'   => 'المدير التنفيذي',
            'admin' => 'مدير النظام',
            'ACC'   => 'محاسب',
            'MT'    => 'فني صيانة',
            'CLT'   => 'عميل',
        ];
    }

   

     public static function values(): array
    {
        return array_keys(self::options());
    }

    public static function label(string $value): ?string
    {
        return self::options()[$value] ?? null;
    }

    public static function is(string $value): bool
    {
        return Auth::check() && Auth::user()->role === $value;
    }


   public static function all(): array
{
    return self::values();
}

}

