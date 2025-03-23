<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MaintenanceRequestsResource;
use Filament\Forms\Form;

class EditMaintenanceRequests extends EditRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    public function form(Form $form): Form
    {
        return (new CreateMaintenanceRequests())->form($form); // إعادة استخدام الفورم
    }
}


// namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

// use App\Filament\Resources\MaintenanceRequestsResource;
// use Filament\Actions;
// use Filament\Resources\Pages\EditRecord;

// class EditMaintenanceRequests extends EditRecord
// {
//     protected static string $resource = MaintenanceRequestsResource::class;

//     protected function getHeaderActions(): array
//     {
//         return [
//             Actions\ViewAction::make(),
//             Actions\DeleteAction::make(),
//         ];
//     }
// }
