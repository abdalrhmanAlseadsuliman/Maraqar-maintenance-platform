<?php

namespace App\Filament\Resources;

// namespace App\Filament\Resources;

use Mpdf\Mpdf;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\RequestType;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use App\Models\MaintenanceRequests;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MaintenanceRequestsResource\Pages;
use App\Filament\Resources\MaintenanceRequestsResource\RelationManagers;


class MaintenanceRequestsResource extends Resource
{
    protected static ?string $model = MaintenanceRequests::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function getPluralLabel(): string
    {
        return 'طلبات الصيانة';
    }


    // public static function getModelLabel(): string
    // {
    //     return 'طلب جديد';
    // }

    public static function getNavigationLabel(): string
    {
        return 'إدارة الطلبات';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('property_id')
                    ->relationship('property', 'name')
                    ->label('اختر العقار')
                    ->required(),

                Forms\Components\Select::make('request_type')
                    ->required()
                    ->options(RequestType::getOptions())
                    ->label('نوع الطلب')
                    ->placeholder('دهان , ابواب , صرف صحي , كهرباء'),

                // Forms\Components\TextInput::make('status')
                //     ->label('حالة الطلب')
                //     ->default('pending')
                //     ->disabled()
                //     ->dehydrated()
                //     ->formatStateUsing(fn () => 'قيد الانتظار')
                //     ->required(),

                Forms\Components\Select::make('status')
                    ->label('حالة الطلب')
                    ->options(['pending' => 'قيد الانتظار'])
                    ->default('pending')
                    ->disabled()
                    ->required(),

                Forms\Components\DateTimePicker::make('submitted_at')
                    ->label('تاريخ الارسال')
                    ->required(),
                Forms\Components\TextInput::make('technician_visits')
                    ->label('زيارات الفنيين')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('problem_description')
                    ->label('وصف المشكلة')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('technician_name')
                    ->label('اسم الفني')
                    ->maxLength(255),
                Forms\Components\Textarea::make('technician_notes')
                    ->label('ملاحظات الفنيين')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('rejection_reason')
                    ->label(' اسباب الرفض إن وجدت ')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('cost')
                    ->label('الكلفة النهائية')
                    ->numeric()
                    ->prefix('$'),

                Section::make('رفع الصور')
                    ->schema([
                        FileUpload::make('images')
                            ->label('تحميل الصور')
                            ->image()
                            ->multiple()
                            ->directory('maintenance-requests')
                            ->required(),
                    ])
                    ->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('request_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('technician_visits')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('technician_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),



                // Tables\Columns\TextColumn::make('images')
                //     ->label('الصور')
                //     ->formatStateUsing(function ($record) {
                //         return $record->images->map(function ($image) {
                //             return '<img src="' . asset('storage/' . $image->image_path) . '" width="60" height="60" style="border-radius: 5px; margin: 2px;">';
                //         })->implode(' ');
                //     })
                //     ->html(),


                Tables\Columns\ImageColumn::make('image_path')
                    ->label('صورة الطلب')
                    ->disk('public')
                    ->width(80)
                    ->height(80)
                    ->getStateUsing(fn ($record) => optional($record->images->first())->image_path ? asset('storage/' . $record->images->first()->image_path) : null),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Action::make('exportPdf')
                    ->label('تصدير PDF') // 🟢 نص الزر
                    // ->icon('heroicon-o-document-download') // 🟢 أيقونة للزر
                    ->color('primary') // 🟢 لون الزر
                    ->action(function ($record) {
                          $html = Blade::render('pdf-action', ['record' => $record]);

                    $mpdf = new Mpdf([
                        'mode' => 'utf-8',
                        'format' => 'A4',
                        'default_font' => 'dejavusans',
                    ]);

                    $mpdf->WriteHTML($html);
                    return response()->streamDownload(function () use ($mpdf) {
                        echo $mpdf->Output('', 'S');
                    }, 'hdhg' . '.pdf');
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

                BulkAction::make('Export to PDF')
                    ->action(function ($records) {
                        $html = Blade::render('pdf-bulk', ['records' => $records]);
                        $mpdf = new Mpdf([
                            'mode' => 'utf-8',
                            'format' => 'A4',
                            'default_font' => 'dejavusans',
                        ]);
                        $mpdf->WriteHTML($html);
                        return response()->streamDownload(function () use ($mpdf) {
                            echo $mpdf->Output('', 'S');
                        }, 'hdhg' . '.pdf');
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\ImagesRelationManager::class,
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->whereHas('property', function ($query) {
    //         $query->where('user_id', auth()->id()); // 🔹 تصفية الطلبات بناءً على مالك العقار
    //     });
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceRequests::route('/'),
            'create' => Pages\CreateMaintenanceRequests::route('/create'),
            'view' => Pages\ViewMaintenanceRequests::route('/{record}'),
            'edit' => Pages\EditMaintenanceRequests::route('/{record}/edit'),
        ];
    }
}
