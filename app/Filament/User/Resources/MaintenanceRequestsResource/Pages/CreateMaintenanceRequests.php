<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

use Carbon\Carbon;
use Filament\Notifications\Actions\Action as NotificationAction;
use Mokhosh\FilamentRating\Components\Rating;
use Mokhosh\FilamentRating\RatingTheme;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Form;
use App\Enums\RequestType;
use Filament\Actions\Action;
use App\Models\Property;
use App\Models\MaintenanceRequests;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\User\Resources\MaintenanceRequestsResource;
use Filament\Actions;

class CreateMaintenanceRequests extends CreateRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    public function getTitle(): string
    {
        return ' إنشاء طلب صيانة ';
    }

    public function getBreadcrumb(): string
    {
        return 'إنشاء طلب صيانة';
    }



    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('إرسال الطلب')
                ->submit('create')
                ->icon('heroicon-m-check'),

            // Action::make('createAnother')
            //     ->label('إرسال وطلب جديد')
            //     ->submit('createAnother'),

            Action::make('cancel')
                ->label('إلغاء')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
    private array $imagesToSave = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']);
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $userId = auth()->id();
        // dd($data);

            // استخراج property_id من البيانات المُرسلة
            $propertyId = $data['property_id'];

            // التحقق من وجود طلب سابق غير مقيم
            $existingUnratedRequest = MaintenanceRequests::whereHas('property', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where('property_id', $propertyId)
                ->where('status', 'completed')
                ->whereNull('rating')
                ->first();
                // dd($existingUnratedRequest);

            // إذا وجد طلب غير مقيم، نمنع إنشاء الطلب الجديد
            if ($existingUnratedRequest !== null) {
                Notification::make()
                    ->title('لا يمكنك إرسال طلب جديد')
                    ->body('يرجى تقييم الطلب السابق قبل إرسال طلب جديد لهذا العقار.')
                    ->danger()
                    ->persistent()
                    ->send();
                    throw ValidationException::withMessages([
                        'property_id' => 'يرجى تقييم الطلب السابق قبل إرسال طلب جديد لهذا العقار.',
                    ]);
            }



        $record = static::getModel()::create($data);

        if (!empty($this->imagesToSave)) {
            $record->images()->createMany(
                collect($this->imagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
            );
        }

        $property = Property::find($record->property_id);

if ($property) {
    $yearsSincePurchase = abs(now()->diffInYears(Carbon::parse($property->sale_date)));
    $requestType = $record->request_type instanceof \BackedEnum
    ? $record->request_type->value
    : $record->request_type;


    $maxYears = match ($requestType) {
        RequestType::PAINTING->value => 1,
        RequestType::DOORS->value => 1,
        RequestType::PLUMBING->value => 2,
        RequestType::ELEC->value => 2,
        RequestType::STRUCTURE->value => 10,
        default => null,
    };
    // dd($yearsSincePurchase, $maxYears);

    if ($maxYears !== null && $yearsSincePurchase >= $maxYears) {
        $requestTypeValue = $record->request_type instanceof \BackedEnum
    ? $record->request_type->value
    : $record->request_type;
        $typeName = RequestType::getOptions()[$requestTypeValue] ?? 'غير معروف';
        $message = "تم رفض طلب الصيانة لأن نوع الطلب هو ($typeName) وتاريخ شراء العقار يتجاوز {$maxYears} سنة.";

        Notification::make()
            ->title('طلب مرفوض!')
            ->body($message)
            ->danger()
            ->send();

        throw ValidationException::withMessages([
            'property_id' => $message,
        ]);
    }
}

// ✅ إشعار النجاح يُرسل دائمًا بعد تحقق كل الشروط وعدم وجود سبب للرفض
Notification::make()
    ->title('تم إرسال الطلب بنجاح')
    ->body('يرجى الانتظار خمس أيام لمعالجة الطلب.')
    ->danger()
    ->persistent()
    ->actions([
        NotificationAction::make('close')->label('تم'),
    ])
    ->send();


        return $record;
    }





    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('معلومات الطلب')->schema([
                // Select::make('property_id')
                //     ->relationship('property', 'name')
                //     ->label('اختر العقار')
                //     ->required(),
                Select::make('property_id')
                    ->relationship(
                        name: 'property',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query->where('user_id', auth()->id())
                    )
                    ->label('اختر العقار')
                    ->required(),
                Select::make('request_type')
                    ->options(\App\Enums\RequestType::getOptions())
                    ->label('نوع الطلب')
                    ->required(),
                Select::make('status')
                    ->label('حالة الطلب')
                    ->options(['pending' => 'تم الاستلام'])
                    ->default('pending')
                    ->disabled()
                    ->required(),
            ]),
            Section::make('تفاصيل المشكلة')->schema([
                Textarea::make('problem_description')->label('وصف المشكلة')->required(),
            ]),

            Section::make('مرفقات طلب الصيانة')->schema([

                FileUpload::make('images')
                    ->label('تحميل الصور')
                    ->image()
                    ->multiple()
                    ->directory('maintenance-requests')
                    ->required(),
            ]),
            Rating::make('rating')
    ->label('تقييم العميل')
    ->stars(5) // عدد النجوم
    ->theme(RatingTheme::Simple) // شكل النجوم (Simple, HalfStars)
    ->allowZero() // يتيح اختيار 0
    ->size('lg') // حجم النجوم (xs, sm, md, lg, xl)
    ->color('warning')->visible(fn (?MaintenanceRequests $record) => $record?->status === 'completed'),

        ]);

    }
}
