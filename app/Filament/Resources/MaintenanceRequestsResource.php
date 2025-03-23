<?php

namespace App\Filament\Resources;


use Filament\Resources\Resource;
use App\Models\MaintenanceRequests;
use App\Filament\Resources\MaintenanceRequestsResource\Pages;



class MaintenanceRequestsResource extends Resource
{
    protected static ?string $model = MaintenanceRequests::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getPluralLabel(): string
    {
        return 'طلبات الصيانة';
    }


    // public static function getModelLabel(): string
    // {
    //     return 'طلب جديد';
    // }

    public static function getNavigationLabel(): string
    {
        return 'إدارة الطلبات';
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceRequests::route('/'),
            'create' => Pages\CreateMaintenanceRequests::route('/create'),
            'view' => Pages\ViewMaintenanceRequests::route('/{record}'),
            'edit' => Pages\EditMaintenanceRequests::route('/{record}/edit'),
        ];
    }
}
