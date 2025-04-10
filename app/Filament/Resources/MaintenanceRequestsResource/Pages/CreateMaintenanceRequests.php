<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use App\Enums\UserRole;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;


use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\MaintenanceRequestsResource;


class CreateMaintenanceRequests extends CreateRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    private array $imagesToSave = [];
    private array $solutionImagesToSave = [];



    // 🟢 تعديل البيانات قبل الحفظ (استخراج الصور)
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // حفظ الصور الخاصة بالحقل images
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']); // إزالة الصور الخاصة بالحقل images

        // حفظ الصور الخاصة بالحقل solutionimages
        $this->solutionImagesToSave = $data['solutionImages'] ?? [];
        unset($data['solutionImages']); // إزالة الصور الخاصة بالحقل solutionimages

        return $data;
    }


    // 🟢 تعديل عملية إنشاء الطلب للحصول على `$record`
    protected function handleRecordCreation(array $data): Model
    {
        // إنشاء السجل
        $record = static::getModel()::create($data);

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

            Section::make(' المرفقات')
                ->schema([

                    FileUpload::make('images')
                        ->label('تحميل الصور')
                        ->image()
                        ->multiple()
                        ->directory('maintenance-requests')
                        ->required(),
                ]),


            Section::make('التكلفة والمرفقات')
                ->schema([
                    TextInput::make('cost')
                        ->label('الكلفة النهائية')
                        ->numeric()
                        ->prefix('$'),

                    FileUpload::make('solutionImages')
                        ->label('تحميل الصور')
                        ->image()
                        ->multiple()
                        ->directory('maintenance-requests-cost')
                        ->required(),
                ])
                // ->visible(fn() => Auth::user()->role === UserRole::MAINTTECH),
        ]);
    }
}
