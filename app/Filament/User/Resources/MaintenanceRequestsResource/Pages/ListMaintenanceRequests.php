<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

use App\Filament\User\Resources\MaintenanceRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceRequests extends ListRecords
{
    protected static string $resource = MaintenanceRequestsResource::class;

    public function getTitle(): string
    {
        return ' قائمة طلبات الصيانة ';
    }

    public function getBreadcrumb(): string
    {
        return 'قائمة طلبات الصيانة ';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء طلب صيانة'),
        ];
    }
}
