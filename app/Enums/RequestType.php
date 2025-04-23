<?php

namespace App\Enums;


enum RequestType: string
{
    case PAINTING = 'painting';
    case DOORS = 'doors';
    case PLUMBING = 'plumbing';

    public static function getOptions(): array
    {
        return [
            self::PAINTING->value => 'دهانة',
            self::DOORS->value => 'أبواب',
            self::PLUMBING->value => 'صحية',
        ];
    }
}
