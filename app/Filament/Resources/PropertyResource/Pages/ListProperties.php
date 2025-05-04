<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء عقار جديد'),

        ];
    }
    public function getTitle(): string
    {
        return ' عقارات المؤسسة ';
    }

    public function getBreadcrumb(): string
    {
        return 'قائمة العقارات';
    }
}
