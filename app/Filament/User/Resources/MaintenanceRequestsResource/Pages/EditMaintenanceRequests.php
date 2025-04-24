<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

use App\Filament\User\Resources\MaintenanceRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Mokhosh\FilamentRating\Components\Rating;
use Mokhosh\FilamentRating\RatingTheme;
use App\Models\MaintenanceRequests;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class EditMaintenanceRequests extends EditRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات الطلب')->schema([

                Rating::make('rating')
                    ->label('تقييم العميل')
                    ->stars(5) // عدد النجوم
                    ->theme(RatingTheme::Simple) // شكل النجوم (Simple, HalfStars)
                    ->allowZero() // يتيح اختيار 0
                    ->size('lg') // حجم النجوم (xs, sm, md, lg, xl)
                    ->color('warning')->visible(fn(?MaintenanceRequests $record) => $record?->status === 'completed'),

            ]),
        ]);
    }
}
