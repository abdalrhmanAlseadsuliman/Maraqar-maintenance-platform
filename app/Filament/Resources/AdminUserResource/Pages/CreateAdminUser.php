<?php

namespace App\Filament\Resources\AdminUserResource\Pages;

use App\Filament\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateAdminUser extends CreateRecord
{
    protected static string $resource = AdminUserResource::class;

     public function getTitle(): string
    {
        return 'إضافة موظف جديد';
    }

    public function getBreadcrumb(): string
    {
        return 'إضافة';
    }

     protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('إضافة موظف')
                ->submit('create')
                ->icon('heroicon-m-check'),

            Action::make('createAnother')
                ->label('إضافة وإضافة موظف اخر')
                ->submit('createAnother'),

            Action::make('cancel')
                ->label('إلغاء')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
}
