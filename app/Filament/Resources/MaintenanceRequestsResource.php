<?php

namespace App\Filament\Resources;


use Filament\Resources\Resource;
use App\Models\MaintenanceRequests;
use Illuminate\Database\Eloquent\Model;
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
    public static function canDelete(Model $record): bool
{
    return auth()->user()->can('delete', $record);
}

}
