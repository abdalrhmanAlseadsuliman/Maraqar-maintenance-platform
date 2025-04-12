<?php

namespace App\Filament\User\Resources\PropertyResource\Pages;

use App\Filament\User\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Property;
use Filament\Notifications\Notification;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    protected static ?string $title = 'إضافة عقار جديد';
    
}
