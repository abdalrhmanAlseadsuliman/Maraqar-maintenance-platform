<?php

namespace App\Filament\Resources\AdminUserResource\Pages;

use App\Filament\Resources\AdminUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdminUser extends ViewRecord
{
    protected static string $resource = AdminUserResource::class;

    public function getTitle(): string
    {
        return 'عرض بيانات الموظف';
    }

    public function getBreadcrumb(): string
    {
        return 'عرض';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
        ];
    }
}
