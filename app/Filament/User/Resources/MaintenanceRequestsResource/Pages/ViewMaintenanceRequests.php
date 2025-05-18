<?php

namespace App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

// use App\Filament\User\Resources\MaintenanceRequestsResource;

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
        return $form->schema([
            Section::make('معلومات الطلب')
                ->schema([
                    TextInput::make('property_id')->label('العقار')->disabled(),
                    TextInput::make('request_type')->label('نوع الطلب')->disabled(),
                    Select::make('status')
                        ->label('حالة الطلب')
                        ->options([
                            'pending' => 'تم الاستلام',
                            'in_progress' => 'جاري العمل',
                            'completed' => 'مكتمل',
                        ])
                        ->disabled(),

                    DateTimePicker::make('created_at')->label('تاريخ الإرسال')->disabled(),
                ]),

            Section::make('تفاصيل المشكلة')
                ->schema([
                    Textarea::make('problem_description')->label('وصف المشكلة')->disabled(),
                ]),

            Section::make('المعلومات الفنية')
                ->schema([
                    TextInput::make('technician_name')->label('اسم الفني')->disabled(),
                    Textarea::make('technician_notes')->label('ملاحظات الفنيين')->disabled(),
                ]),


            Section::make(' رسائل من الفني الى العميل ')
                ->schema([

                    Textarea::make('technician_messages')
                        ->required()
                        ->label('ارسال رسالة للعميل')
                        ->disabled(),
                ]),

            Section::make('التكلفة والمرفقات')
                ->schema([
                    TextInput::make('cost')->label('الكلفة النهائية')->prefix('$')->disabled(),
                ]),






            // Section::make('الصور المرفقة')
            //     ->schema([
            //         Repeater::make('images')
            //             ->relationship('images') // جلب الصور من العلاقة
            //             ->schema([
            //                 FileUpload::make('image_path')
            //                     ->label('الصورة')
            //                     ->image() // تحديد أن هذا ملف صورة
            //                     ->disk('public_direct') // تحديد مكان التخزين
            //                     ->disabled() // منع التعديل
            //                     ->previewable(true) // إظهار المعاينة
            //             ]),
            //     ])
            //     ->columnSpanFull()
            Section::make('الصور المرفقة')
                ->schema([
                    Repeater::make('images')
                        ->relationship('images') // جلب الصور من العلاقة
                        ->schema([
                            FileUpload::make('image_path')
                                ->label('الصورة')
                                ->image() // تحديد أن هذا ملف صورة
                                ->disk('public_direct') // تحديد مكان التخزين
                                ->disabled() // منع التعديل
                                ->previewable(true) // إظهار المعاينة
                        ]),
                ])
                ->columnSpanFull(),

        ]);
    }
}
