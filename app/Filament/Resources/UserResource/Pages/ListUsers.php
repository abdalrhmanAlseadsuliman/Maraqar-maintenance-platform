<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إنشاء عميل جديد'),

        ];
    }
    public function getTitle(): string
    {
        return ' إدارة العملاء';
    }

    public function getBreadcrumb(): string
    {
        return 'قائمة';
    }
}
