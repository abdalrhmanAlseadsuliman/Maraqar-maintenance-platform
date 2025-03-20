<?php
namespace App\Enums;

class UserRole
{
    public const CHAIRMAN   = 'CHR';
    public const EXECDIR    = 'EDR';
    public const ADMIN      = 'admin';
    public const ACCOUNTANT = 'ACC';
    public const MAINTTECH  = 'MT';
    public const CLIENT     = 'CLT';

    public static function all(): array
    {
        return [
            self::CHAIRMAN,
            self::EXECDIR,
            self::ADMIN,
            self::ACCOUNTANT,
            self::MAINTTECH,
            self::CLIENT,
        ];
        // Chairman →   CHR
        // ExecDir →    EDR
        // Supervisor → SPV
        // Accountant → ACC
        // MaintTech →  MT
        // Client →     CLT
    }
}
