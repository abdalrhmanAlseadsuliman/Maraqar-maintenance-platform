<?php

namespace App\Filament\Resources\AdminUserResource\Pages;

use App\Filament\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditAdminUser extends EditRecord
{
    protected static string $resource = AdminUserResource::class;

 public function getTitle(): string
    {
        return 'تعديل بيانات الموظف';
    }

    public function getBreadcrumb(): string
    {
        return 'تعديل';
    }


    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('تحديث بيانات الموظف')
                ->submit('save')
                ->icon('heroicon-m-pencil'),

            Action::make('cancel')
                ->label('إلغاء')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('عرض'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
}
