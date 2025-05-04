<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;


    public function getTitle(): string
    {
        return 'تعديل بيانات العميل';
    }

    public function getBreadcrumb(): string
    {
        return 'تعديل';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('عرض بيانات العميل'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }


    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('تحديث بيانات العميل')
                ->submit('save')
                ->icon('heroicon-m-pencil'),

            Action::make('cancel')
                ->label('إلغاء')
                ->url($this->getResource()::getUrl('index')),
        ];
    }



}
