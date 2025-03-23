<?php

namespace App\Filament\User\Resources;

use Mpdf\Mpdf;
use Filament\Forms;
use Filament\Tables;
use App\Models\Property;
use Filament\Forms\Form;
use App\Enums\RequestType;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\MaintenanceRequests;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\User\Resources\MaintenanceRequestsResource\Pages;
use App\Filament\User\Resources\MaintenanceRequestsResource\RelationManagers;
use Filament\Tables\Actions\CreateAction;

class MaintenanceRequestsResource extends Resource
{
    protected static ?string $model = MaintenanceRequests::class;


    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'طلبات الصيانة';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('property_id')
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('request_type')
                    ->label('نوع الطلب')
                    ->options(RequestType::getOptions())
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'in_progress' => 'قيد التنفيذ',
                        'completed' => 'مكتمل',
                        'rejected' => 'مرفوض',
                    ])
                    ->label('حالة الطلب')
                    ->required(),

                Forms\Components\DateTimePicker::make('submitted_at')
                    ->required(),

                Forms\Components\TextInput::make('technician_visits')
                    ->required()
                    ->numeric()
                    ->default(0),

                Forms\Components\Textarea::make('problem_description')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('technician_notes')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('rejection_reason')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('technician_name')
                    ->maxLength(255)
                    ->default(null),

                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('property', function ($query) {
            $query->where('user_id', auth()->id());
        });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function create(): CreateAction
    {
        return parent::create()
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('تم تسجيل المستخدم')
                    ->body('تم إنشاء المستخدم بنجاح.')
            )
            ->after(function ($record) {
                $property = Property::find($record->property_id);

                if ($property && now()->diffInYears($property->sale_date) >= 1) {
                    // تحديث حالة الطلب إلى "مرفوض"
                    $record->update(['status' => 'rejected']);

                    // إرسال إشعار للمستخدم
                    Notification::make()
                        ->title('طلب مرفوض!')
                        ->body('تم رفض طلب الصيانة لأن تاريخ شراء العقار يتجاوز عامًا.')
                        ->danger()
                        ->send();
                }
            });
    }

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
