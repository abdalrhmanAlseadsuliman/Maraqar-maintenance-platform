<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\MaintenanceRequestsResource;

class EditMaintenanceRequests extends EditRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;
    private array $imagesToSave = [];
    private array $solutionImagesToSave = [];

    // 🟢 تعديل البيانات قبل التحديث (استخراج الصور)
    protected function mutateFormDataBeforeSave(array $data): array
    {

        // حفظ الصور الخاصة بالحقل images
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']); // إزالة الصور الخاصة بالحقل images

        // حفظ الصور الخاصة بالحقل solutionimages
        $this->solutionImagesToSave = $data['solutionImages'] ?? [];
        unset($data['solutionImages']); // إزالة الصور الخاصة بالحقل solutionimages

        return $data;
    }

    // 🟢 تعديل عملية التحديث للسجل
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // تحديث السجل
        // dd($data);
        $record->update($data);

        // حذف الصور القديمة (اختياري)
        // إذا كنت ترغب في حذف الصور القديمة قبل إضافة صور جديدة:
        // $record->images()->delete();  // حذف جميع الصور القديمة
        // $record->solutionimages()->delete();  // حذف صور الحلول القديمة

        // حفظ الصور الخاصة بالحقل images
        if (!empty($this->imagesToSave)) {

            $record->images()->createMany(
                collect($this->imagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
            );
        }

        // حفظ الصور الخاصة بالحقل solutionimages
        if (!empty($this->solutionImagesToSave)) {
            $record->solutionImages()->createMany(
                collect($this->solutionImagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
            );
        }

        return $record;
    }

    public function form(Form $form): Form
    {
        return (new CreateMaintenanceRequests())->form($form); // إعادة استخدام الفورم
    }
}




