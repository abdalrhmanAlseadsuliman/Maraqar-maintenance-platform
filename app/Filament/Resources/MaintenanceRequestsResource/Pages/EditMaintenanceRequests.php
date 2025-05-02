<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use App\Enums\UserRole;
use Filament\Forms\Form;
use App\Models\MaintenanceRequests;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\FileUpload;
use App\Notifications\NewPushNotification;
use App\Filament\Resources\MaintenanceRequestsResource;
use App\Notifications\NewMaintenanceRequestNotification;

class EditMaintenanceRequests extends EditRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;
    private array $imagesToSave = [];
    private array $solutionImagesToSave = [];

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->imagesToSave = $data['images'] ?? [];
        unset($data['images']);

        $this->solutionImagesToSave = $data['solutionImages'] ?? [];
        unset($data['solutionImages']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // ✅ حفظ الحالة السابقة قبل التحديث
        $oldStatus = $record->status;
        $property = \App\Models\Property::find($record->property_id);
        $user = $property?->owner;
        // تحديث السجل
        $record->update($data);

        // حفظ الصور الجديدة
        if (!empty($this->imagesToSave)) {
            $record->images()->createMany(
                collect($this->imagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
            );
        }

        if (!empty($this->solutionImagesToSave)) {
            $record->solutionImages()->createMany(
                collect($this->solutionImagesToSave)->map(fn($image) => ['image_path' => $image])->toArray()
            );
        }

        // ✅ إرسال إشعار فقط إذا تغيرت الحالة إلى "مرفوض"
        if ($oldStatus !== 'rejected' && $record->status === 'rejected' && $record->status_message) {


            if ($user) {
                $user->notify(new NewMaintenanceRequestNotification(
                    $record,
                    title: 'تم رفض طلب الصيانة رقم ' . $record->id,
                    body: $record->status_message
                ));
                $user->notify(new NewPushNotification(
                    title: 'تم رفض طلب الصيانة رقم ' . $record->id,
                    body: $record->status_message,
                    url: '/user/maintenance-requests/' . $record->id
                ));
            }
        }if ($user && $record->technician_messages) {
            $user->notify(new NewMaintenanceRequestNotification(
                $record,
                title: 'رسالة من الفني بخصوص طلب الصيانة رقم' . $record->id,
                body: $record->status_message
            ));
            $user->notify(new NewPushNotification(
                title: 'رسالة من الفني بخصوص طلب الصيانة رقم ' . $record->id,
                body: $record->technician_messages,
                url: '/user/maintenance-requests/' . $record->id
            ));
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
                        ->required()
                        ->disabled(),

                    Select::make('request_type')
                        ->required()
                        ->options(\App\Enums\RequestType::getOptions())
                        ->label('نوع الطلب')
                        ->placeholder('دهان , ابواب , صرف صحي , كهرباء')
                        ->disabled(),

                    Select::make('status')
                        ->label('حالة الطلب')
                        ->options(function () {
                            if (UserRole::is('MT')) {
                                return [
                                    'in_progress' => 'جاري العمل',
                                    'completed' => 'مكتمل',
                                ];
                            }

                            return [
                                'pending' => 'تم الاستلام',
                                'in_progress' => 'جاري العمل',
                                'completed' => 'مكتمل',
                                'rejected' => 'مرفوض',
                            ];
                        })
                        ->disabled(fn() => !(
                            UserRole::is('admin') ||
                            UserRole::is('EDR') ||
                            UserRole::is('MT')
                        ))
                        ->reactive(),

                    Select::make('status_message')
                        ->required()
                        ->label('رسالة الرفض')
                        ->options(function () {
                            return [
                                'نعتذر عميلنا العزيز عن خدمتك وذلك يتضح لنا بان المشكله ناتجه عن سوء استخدام' =>
                                    'نعتذر عميلنا العزيز عن خدمتك وذلك يتضح لنا بان المشكله ناتجه عن سوء استخدام',
                                'نعتذر عميلنا العزيز عن خدمتك لقد تم اجراء تعديل على الوحده مما اخل بعقد الضمان المتفق عليه' =>
                                    'نعتذر عميلنا العزيز عن خدمتك لقد تم اجراء تعديل على الوحده مما اخل بعقد الضمان المتفق عليه',
                                'نفيدك عميلنا العزيز بانه تم استلام طلبكم وجاري مناقشته وتكليف الفني خلال 5 أيام عمل نرجوا الانتباه بانه سيتواصل معكم الفني الرجاء تنسيق الموعد المناسب للزياره وعدم الرد خلال 3 اتصالات للفني يلغى بعدها الطلب شاكرين لكن تعاونكم' =>
                                    'نفيدك عميلنا العزيز بانه تم استلام طلبكم وجاري مناقشته وتكليف الفني خلال 5 أيام عمل نرجوا الانتباه بانه سيتواصل معكم الفني الرجاء تنسيق الموعد المناسب للزياره وعدم الرد خلال 3 اتصالات للفني يلغى بعدها الطلب شاكرين لكن تعاونكم',
                            ];
                        })
                        ->visible(fn($get) => $get('status') === 'rejected'),
                ]),
                Textarea::make('technician_messages')
                ->required()
                ->label('رسائل للعميل'),



            Section::make('تفاصيل المشكلة')
                ->schema([
                    Textarea::make('problem_description')
                        ->label('وصف المشكلة')
                        ->required()
                        ->disabled(fn() => UserRole::is('MT')),
                ]),

            Section::make('معلومات فني الصيانة')
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
                ])
                ->visible(fn() => UserRole::is('MT')),

            Section::make('ملاحظات المدير التنفيذي')
                ->schema([
                    Textarea::make('executive_director_notes')
                        ->label('ملاحظات المدير التنفيذي'),
                ])
                ->visible(fn() => UserRole::is('EDR')),

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
                        ->directory('maintenance-requests-cost'),
                ])
                ->visible(fn() => UserRole::is('MT')),
        ]);
    }
}
