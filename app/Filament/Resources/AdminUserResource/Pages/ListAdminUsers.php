<?php

namespace App\Filament\Resources\AdminUserResource\Pages;

use App\Filament\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminUsers extends ListRecords
{
    protected static string $resource = AdminUserResource::class;

      public function getTitle(): string
    {
        return ' إدارة الموظفين';
    }

    public function getBreadcrumb(): string
    {
        return 'قائمة';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة موظف جديد'),
        ];
    }
}
