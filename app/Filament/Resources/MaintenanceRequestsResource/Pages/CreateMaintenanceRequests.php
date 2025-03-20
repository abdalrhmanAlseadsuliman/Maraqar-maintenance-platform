<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MaintenanceRequestsResource;

class CreateMaintenanceRequests extends CreateRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    private array $imagesToSave = [];

    // 🟢 تعديل البيانات قبل الحفظ (استخراج الصور)
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']); // إزالة الصور حتى لا تسبب خطأ أثناء الحفظ

        return $data;
    }

    // 🟢 تعديل عملية إنشاء الطلب للحصول على `$record`
    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        // 🟢 بعد إنشاء الطلب، حفظ الصور
        if (!empty($this->imagesToSave)) {
            $record->images()->createMany(
                collect($this->imagesToSave)->map(fn ($image) => ['image_path' => $image])->toArray()
            );
        }

        return $record;
    }
}
