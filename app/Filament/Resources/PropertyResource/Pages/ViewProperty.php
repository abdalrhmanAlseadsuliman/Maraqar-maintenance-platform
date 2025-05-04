<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PropertyResource;

class ViewProperty extends ViewRecord
{
    protected static string $resource = PropertyResource::class;


    public function getTitle(): string
    {
        return 'عرض بيانات العقار';
    }

    public function getBreadcrumb(): string
    {
        return 'عرض بيانات العقار';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
            Action::make('back')
                ->label('رجوع')
                ->icon('heroicon-m-arrow-left')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

}
