<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

use App\Filament\User\Resources\MaintenanceRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Property;
use Carbon\Carbon;

class CreateMaintenanceRequests extends CreateRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = static::getModel()::create($data);

        $property = Property::find($record->property_id);

        if ($property) {
            $saleDate = Carbon::parse($property->sale_date);


            // dd('فرق السنوات:', now()->diffInYears($saleDate));

            if (now()->diffInYears($saleDate) <= 1) {
                // dump('✅ الطلب سيتم رفضه لأن الفرق أكبر من سنة.');

                $record->update(['status' => 'rejected']);

                Notification::make()
                    ->title('طلب مرفوض!')
                    ->body('تم رفض طلب الصيانة لأن تاريخ شراء العقار يتجاوز عامًا.')
                    ->danger()
                    ->send();
            } else {
                dump('❌ الطلب مقبول لأن الفرق أقل من سنة.');
            }
        }

        return $record;
    }

}

