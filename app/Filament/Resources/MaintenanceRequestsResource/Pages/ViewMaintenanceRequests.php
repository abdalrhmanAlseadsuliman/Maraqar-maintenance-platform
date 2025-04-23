<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Image;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\View; // استدعاء View Component
use Filament\Forms\Components\FileUpload;

use Filament\Forms\Components\ImageUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\MaintenanceRequestsResource;

class ViewMaintenanceRequests extends ViewRecord
{
    protected static string $resource = MaintenanceRequestsResource::class;

    public function getTitle(): string
    {
        return 'عرض الطلب';
    }

    public function getBreadcrumb(): string
    {
        return 'عرض الطلب';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
        ];
    }

    public function form(Form $form): Form
    {
        //  dd($form);
        return $form->schema([
            Section::make('معلومات الطلب')
                ->schema([
                    TextInput::make('property_id')->label('العقار')->disabled(),
                    TextInput::make('request_type')->label('نوع الطلب')->disabled(),
                    Select::make('status')
                        ->label('حالة الطلب')
                        ->options([
                            'pending' => 'قيد الانتظار',
                            'in_progress' => 'قيد التنفيذ',
                            'completed' => 'مكتمل',
                        ])
                        ->disabled(),

                    DateTimePicker::make('submitted_at')->label('تاريخ الإرسال')->disabled(),
                ]),

            Section::make('تفاصيل المشكلة')
                ->schema([
                    Textarea::make('problem_description')->label('وصف المشكلة')->disabled(),

                ]),

            Section::make('معلومات فني الصيانة')
                ->schema([
                    TextInput::make('technician_name')->label('اسم الفني')->disabled(),
                    Textarea::make('technician_notes')->label('ملاحظات الفنيين')->disabled(),
                ]),

            // Section::make('معلومات المستخدم ')
            //     ->schema([
            //         TextInput::make('property.owner.name')
            //             ->label('اسم المالك')
            //             ->disabled(),

            //         TextInput::make('property.owner.phone')
            //             ->label('رقم هاتف المالك')
            //             ->disabled(),
            //     ]),

            Section::make('معلومات المستخدم ')
                ->schema([
                    Placeholder::make('owner_name')
                        ->label('اسم المالك')
                        ->content(fn($record) => optional($record->property?->owner)->name ?? '-'),

                    Placeholder::make('owner_phone')
                        ->label('رقم هاتف المالك')
                        ->content(fn($record) => optional($record->property?->owner)->phone ?? '-'),
                ]),



            Section::make('ملاحظات المدير التنفيذي')
                ->schema([
                    Textarea::make('executive_director_notes')
                        ->label('ملاحظات المدير التنفيذي'),
                ]),

            Section::make('التكلفة والمرفقات')
                ->schema([
                    TextInput::make('cost')->label('الكلفة النهائية')->prefix('$')->disabled(),
                ]),

            Section::make('الصور المرفقة')
                ->schema([
                    Repeater::make('images')
                        ->relationship('images') // جلب الصور من العلاقة
                        ->schema([
                            FileUpload::make('image_path')
                                ->label('الصورة')
                                ->image() // تحديد أن هذا ملف صورة
                                ->disk('public') // تحديد مكان التخزين
                                ->disabled() // منع التعديل
                                ->previewable(true) // إظهار المعاينة
                        ]),
                ])
                ->columnSpanFull()
        ]);
    }
}
