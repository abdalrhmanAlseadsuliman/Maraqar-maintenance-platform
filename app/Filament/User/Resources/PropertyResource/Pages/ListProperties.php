<?php

namespace App\Filament\User\Resources\PropertyResource\Pages;

use App\Filament\User\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    // protected static ?string $title = 'عرض العقارات';

    public function getTitle(): string
    {
        return 'عرض العقارات';
    }

    public function getBreadcrumb(): string
    {
        return ' قائمة العقارات';
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
