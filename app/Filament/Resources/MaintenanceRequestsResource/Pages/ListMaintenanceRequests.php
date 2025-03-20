<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use App\Filament\Resources\MaintenanceRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceRequests extends ListRecords
{
    protected static string $resource = MaintenanceRequestsResource::class;

    public function getTitle(): string
    {
        return ' إدارة طلبات الصيانة';
    }

    public function getBreadcrumb(): string
    {
        return 'قائمة الطلبات';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(' إنشاء طلب جديد'),
        ];
    }
}
