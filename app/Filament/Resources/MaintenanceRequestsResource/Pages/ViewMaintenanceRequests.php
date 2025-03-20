<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;


use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Form;
use App\Enums\RequestType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\FileUpload;
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
        return $form
            ->schema([
                Section::make('تفاصيل الطلب')
                    ->schema([
                        Grid::make(2) // 🔹 عرض هذه الحقول في عمودين
                            ->schema([
                                Select::make('property_id')
                                    ->relationship('property', 'name')
                                    ->label('اختر العقار')
                                    ->disabled(),

                                Select::make('request_type')
                                    ->options(RequestType::getOptions())
                                    ->label('نوع الطلب')
                                    ->disabled(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('حالة الطلب')
                                    ->options(['pending' => 'قيد الانتظار'])
                                    ->default('pending')
                                    ->disabled(),

                                DateTimePicker::make('submitted_at')
                                    ->label('تاريخ الإرسال')
                                    ->disabled(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('technician_visits')
                                    ->label('زيارات الفنيين')
                                    ->numeric()
                                    ->disabled(),

                                TextInput::make('cost')
                                    ->label('الكلفة النهائية')
                                    ->numeric()
                                    ->prefix('$')
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('تفاصيل المشكلة')
                    ->schema([
                        Textarea::make('problem_description')
                            ->label('وصف المشكلة')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Section::make('تفاصيل الفني')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('technician_name')
                                    ->label('اسم الفني')
                                    ->maxLength(255)
                                    ->disabled(),

                                Textarea::make('technician_notes')
                                    ->label('ملاحظات الفنيين')
                                    ->disabled(),
                            ]),
                    ]),

                Section::make('معلومات إضافية')
                    ->schema([
                        Textarea::make('rejection_reason')
                            ->label('أسباب الرفض إن وجدت')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                // 🔹 عرض الصور المرفقة
                Section::make('الصور المرفقة')

                        ->schema([
                            Repeater::make('images')
                                ->relationship('images')
                                ->schema([
                                    FileUpload::make('image_path')
                                        ->label('صورة الطلب')
                                        ->disk('public')
                                        ->image()
                                        ->disabled() // لجعلها غير قابلة للتعديل
                                        ->directory('maintenance-requests'),
                                ]),
                        ]),
            ]);
    }
}
