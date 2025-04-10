<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MaintenanceRequestsResource;

class EditMaintenanceRequests extends EditRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    public function form(Form $form): Form
    {
        return (new CreateMaintenanceRequests())->form($form); // إعادة استخدام الفورم
    }
    private array $imagesToSave = [];

    // 🟢 تعديل البيانات قبل الحفظ (استخراج الصور)
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']); // إزالة الصور حتى لا تسبب خطأ أثناء الحفظ

        return $data;
    }

    // 🟢 تعديل عملية إنشاء الطلب للحصول على `$record`
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // 🟢 تحديث بيانات الطلب نفسه
        $record->update($data);

        // 🟢 تحديث الصور إن وُجدت صور جديدة
        if (!empty($this->imagesToSave)) {
            // حذف الصور القديمة إن أردت (اختياري):
            // $record->images()->delete();

            // إضافة الصور الجديدة
            $record->images()->createMany(
                collect($this->imagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
            );
        }

        return $record;
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->imagesToSave = $data['images'] ?? []; // 🟢 نحفظ الصور مؤقتًا
        unset($data['images']);                     // 🔴 نزيلها من البيانات حتى لا تتسبب بخطأ

        return $data;
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
