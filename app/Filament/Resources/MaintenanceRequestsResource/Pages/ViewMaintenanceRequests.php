<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use App\Filament\Resources\MaintenanceRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMaintenanceRequests extends ViewRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
