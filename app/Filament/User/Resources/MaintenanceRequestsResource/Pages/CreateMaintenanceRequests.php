<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

use App\Filament\User\Resources\MaintenanceRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Property;
use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class CreateMaintenanceRequests extends CreateRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = static::getModel()::create($data);

        $property = Property::find($record->property_id);

        if ($property) {
            $saleDate = Carbon::parse($property->sale_date);

            $record = static::getModel()::create($data);

                // 🟢 بعد إنشاء الطلب، حفظ الصور
                if (!empty($this->imagesToSave)) {
                    $record->images()->createMany(
                        collect($this->imagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
                    );
                }
            // dd('فرق السنوات:', now()->diffInYears($saleDate));

            if ( now()->diffInYears($saleDate) >= 1) {
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


    private array $imagesToSave = [];

    // 🟢 تعديل البيانات قبل الحفظ (استخراج الصور)
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']); // إزالة الصور حتى لا تسبب خطأ أثناء الحفظ

        return $data;
    }

    // 🟢 تعديل عملية إنشاء الطلب للحصول على `$record`
    // protected function handleRecordCreation(array $data): Model
    // {
    //     $record = static::getModel()::create($data);

    //     // 🟢 بعد إنشاء الطلب، حفظ الصور
    //     if (!empty($this->imagesToSave)) {
    //         $record->images()->createMany(
    //             collect($this->imagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
    //         );
    //     }

    //     return $record;
    // }
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

