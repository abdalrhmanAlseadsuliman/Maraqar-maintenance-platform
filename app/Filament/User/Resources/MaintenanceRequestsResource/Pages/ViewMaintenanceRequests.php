<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

use App\Filament\User\Resources\MaintenanceRequestsResource;
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
