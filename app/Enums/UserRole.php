<?php
namespace App\Enums;

class UserRole
{
    public const ADMIN = 'admin';
    public const CUSTOMER = 'customer';
    public const TECHNICIAN = 'technician';
    public const SUPERVISOR = 'supervisor';

    public static function all(): array
    {
        return [
            self::ADMIN,
            self::CUSTOMER,
            self::TECHNICIAN,
            self::SUPERVISOR,
        ];
    }
}
