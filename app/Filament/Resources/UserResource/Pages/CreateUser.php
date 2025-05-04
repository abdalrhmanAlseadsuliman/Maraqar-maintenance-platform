<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'إضافة عميل جديد';
    }

    public function getBreadcrumb(): string
    {
        return 'إضافة';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('إضافة عميل')
                ->submit('create')
                ->icon('heroicon-m-check'),

            Action::make('createAnother')
                ->label('إضافة وإضافة عميل اخر')
                ->submit('createAnother'),

            Action::make('cancel')
                ->label('إلغاء')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

}
