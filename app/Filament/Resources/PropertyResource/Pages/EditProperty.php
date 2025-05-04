<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PropertyResource;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    public function getTitle(): string
    {
        return 'تعديل بيانات العقار';
    }

    public function getBreadcrumb(): string
    {
        return 'تعديل';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('عرض العقار'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }


    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('تحديث العقار')
                ->submit('save')
                ->icon('heroicon-m-pencil'),

            Action::make('cancel')
                ->label('إلغاء')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

}
