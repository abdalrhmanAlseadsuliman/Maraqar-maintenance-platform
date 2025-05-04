<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PropertyResource;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    public function getTitle(): string
    {
        return ' إضافة عقار جديد ';
    }

    public function getBreadcrumb(): string
    {
        return 'إضافة';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('إضافة عقار')
                ->submit('create')
                ->icon('heroicon-m-check'),

            Action::make('createAnother')
                ->label('إضافة عقار اخر ')
                ->submit('createAnother'),

            Action::make('cancel')
                ->label('إلغاء')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
}
