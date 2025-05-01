<?php

namespace App\Enums;


enum RequestType: string
{
    case PAINTING = 'painting';
    case DOORS = 'doors';
    case PLUMBING = 'plumbing';
    case ELEC='elec';
    case STRUCTURE='structure';

    public static function getOptions(): array
    {
        return [
            self::STRUCTURE->value=>'مواد انشائية واساسات',
            self::PAINTING->value => 'دهانة',
            self::DOORS->value => 'أبواب',
            self::ELEC->value => 'كهرباء',
            self::PLUMBING->value => 'سباكة',
        ];
    }
}
