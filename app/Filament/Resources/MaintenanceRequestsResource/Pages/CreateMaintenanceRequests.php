<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\MaintenanceRequestsResource;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;


use Illuminate\Database\Eloquent\Model;


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
                collect($this->imagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
            );
        }

        return $record;
    }
    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات الطلب')
                ->schema([
                    Select::make('property_id')
                        ->relationship('property', 'name')
                        ->label('اختر العقار')
                        ->required(),

                    Select::make('request_type')
                        ->required()
                        ->options(\App\Enums\RequestType::getOptions())
                        ->label('نوع الطلب')
                        ->placeholder('دهان , ابواب , صرف صحي , كهرباء'),

                    Select::make('status')
                        ->label('حالة الطلب')
                        ->options(['pending' => 'قيد الانتظار'])
                        ->default('pending')
                        ->disabled()
                        ->required(),

                    DateTimePicker::make('submitted_at')
                        ->label('تاريخ الإرسال')
                        ->required(),
                ]),

            Section::make('تفاصيل المشكلة')
                ->schema([
                    Textarea::make('problem_description')
                        ->label('وصف المشكلة')
                        ->required(),
                ]),

            Section::make('المعلومات الفنية')
                ->schema([
                    TextInput::make('technician_visits')
                        ->label('زيارات الفنيين')
                        ->required()
                        ->numeric()
                        ->default(0),

                    TextInput::make('technician_name')
                        ->label('اسم الفني')
                        ->maxLength(255),

                    Textarea::make('technician_notes')
                        ->label('ملاحظات الفنيين'),
                ]),

            Section::make('التكلفة والمرفقات')
                ->schema([
                    TextInput::make('cost')
                        ->label('الكلفة النهائية')
                        ->numeric()
                        ->prefix('$'),

                    FileUpload::make('images')
                        ->label('تحميل الصور')
                        ->image()
                        ->multiple()
                        ->directory('maintenance-requests')
                        ->required(),
                ]),
        ]);
    }
}






// namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

// use Filament\Actions;
// use Illuminate\Database\Eloquent\Model;
// use Filament\Resources\Pages\CreateRecord;
// use App\Filament\Resources\MaintenanceRequestsResource;

// class CreateMaintenanceRequests extends CreateRecord
// {
//     protected static string $resource = MaintenanceRequestsResource::class;

//     private array $imagesToSave = [];

//     // 🟢 تعديل البيانات قبل الحفظ (استخراج الصور)
//     protected function mutateFormDataBeforeCreate(array $data): array
//     {
//         $this->imagesToSave = $data['images'] ?? [];
//         unset($data['images']); // إزالة الصور حتى لا تسبب خطأ أثناء الحفظ

//         return $data;
//     }

//     // 🟢 تعديل عملية إنشاء الطلب للحصول على `$record`
//     protected function handleRecordCreation(array $data): Model
//     {
//         $record = static::getModel()::create($data);

//         // 🟢 بعد إنشاء الطلب، حفظ الصور
//         if (!empty($this->imagesToSave)) {
//             $record->images()->createMany(
//                 collect($this->imagesToSave)->map(fn ($image) => ['image_path' => $image])->toArray()
//             );
//         }

//         return $record;
//     }
// }
