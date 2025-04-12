<?php

namespace App\Filament\User\Resources\PropertyResource\Pages;

use App\Filament\User\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProperty extends ViewRecord
{
    protected static string $resource = PropertyResource::class;

    public function getTitle(): string
    {
        return 'عرض العقار';
    }

    public function getBreadcrumb(): string
    {
        return 'عرض العقار';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
