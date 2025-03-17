<?php

namespace App\Filament\User\Resources\PropertyResource\Pages;

use App\Filament\User\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Property;
use Filament\Notifications\Notification;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    protected static ?string $title = 'إضافة عقار جديد';
    // protected function beforeCreate(): void
    // {
    //     $property = Property::find($this->data['property_id']);

    //     if ($property && now()->diffInYears($property->sale_date) >= 1) {
    //         // تحديث حالة الطلب إلى "مرفوض"
    //         $this->data['status'] = 'rejected';

    //         // إرسال إشعار للمستخدم
    //         Notification::make()
    //             ->title('طلب مرفوض!')
    //             ->body('تم رفض طلب الصيانة لأن تاريخ شراء العقار يتجاوز عامًا.')
    //             ->danger()
    //             ->send();

    //         // إيقاف العملية لمنع الحفظ في قاعدة البيانات
    //         $this->halt();
    //     }}
}
