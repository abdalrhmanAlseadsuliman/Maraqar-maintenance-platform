<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

use Carbon\Carbon;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Models\Property;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\User\Resources\MaintenanceRequestsResource;

class CreateMaintenanceRequests extends CreateRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    private array $imagesToSave = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']);
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = static::getModel()::create($data);

        if (!empty($this->imagesToSave)) {
            $record->images()->createMany(
                collect($this->imagesToSave)->map(fn ($image) => ['image_path' => $image])->toArray()
            );
        }

        $property = Property::find($record->property_id);

        if ($property && now()->diffInYears(Carbon::parse($property->sale_date)) >= 1) {
            $record->update(['status' => 'rejected']);

            Notification::make()
                ->title('طلب مرفوض!')
                ->body('تم رفض طلب الصيانة لأن تاريخ شراء العقار يتجاوز عامًا.')
                ->danger()
                ->send();
        } else {
            Notification::make()
                ->title('تم تسجيل الطلب')
                ->body('تم إنشاء الطلب بنجاح.')
                ->success()
                ->send();
        }

        return $record;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات الطلب')->schema([
                Select::make('property_id')
                    ->relationship('property', 'name')
                    ->label('اختر العقار')
                    ->required(),
                Select::make('request_type')
                    ->options(\App\Enums\RequestType::getOptions())
                    ->label('نوع الطلب')
                    ->required(),
                Select::make('status')
                    ->label('حالة الطلب')
                    ->options(['pending' => 'قيد الانتظار'])
                    ->default('pending')
                    ->disabled()
                    ->required(),

            ]),
            Section::make('تفاصيل المشكلة')->schema([
                Textarea::make('problem_description')->label('وصف المشكلة')->required(),
            ]),
            Section::make('المعلومات الفنية')->schema([
                TextInput::make('technician_visits')->label('زيارات الفنيين')->numeric()->default(0)->required(),
                TextInput::make('technician_name')->label('اسم الفني')->maxLength(255),
                Textarea::make('technician_notes')->label('ملاحظات الفنيين'),
            ]),


            Section::make('مرفقات طلب الصيانة')->schema([

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
