<?php

namespace App\Filament\User\Resources;

use Mpdf\Mpdf;
use Filament\Tables;
use App\Enums\RequestType;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\MaintenanceRequests;
use Mokhosh\FilamentRating\Columns\RatingColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

class MaintenanceRequestsResource extends Resource
{
    protected static ?string $model = MaintenanceRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'طلبات الصيانة';
    public static function getPluralLabel(): string
    {
        return 'طلبات الصيانة';
    }

    public static function getNavigationLabel(): string
    {
        return 'إدارة الطلبات';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property.name')->sortable()->label('اسم العقار'),
                // Tables\Columns\TextColumn::make('request_type')->searchable()->label('نوع الطلب'),
                // Tables\Columns\TextColumn::make('status')->label('حالة الطلب'),

                Tables\Columns\TextColumn::make('request_type')
                    ->label('نوع الطلب')
                    ->searchable()
                    ->formatStateUsing(fn($state) => RequestType::getOptions()[$state->value] ?? $state->value),

                Tables\Columns\TextColumn::make('status')
                    ->label('حالة الطلب')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'قيد الانتظار',
                            'in_progress' => 'قيد التنفيذ',
                            'completed' => 'مكتمل',
                            'rejected' => 'مرفوض',
                            default => $state,
                        };
                    }),

                Tables\Columns\TextColumn::make('technician_name')->searchable()->label(' اسم الفني '),
                Tables\Columns\TextColumn::make('technician_visits')->numeric()->sortable()->label(' عدد زيارات الفني المختص '),
                Tables\Columns\TextColumn::make('cost')->money()->sortable()->label(' الكلفة '),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label(' تاريخ تقديم الطلب ')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->label('تاريخ تعديل الطلب')->toggleable(isToggledHiddenByDefault: true),
                RatingColumn::make('rating')
    ->label('تقييم')
    ->sortable()
    ->alignCenter(),
                ImageColumn::make('images')
                    ->label('الصور')
                    ->disk('public')
                    ->width(80)
                    ->height(80)
                    ->getStateUsing(fn($record) => optional($record->images->first())->image_path ? asset('storage/' . $record->images->first()->image_path) : null),
            ])

            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تقييم')->visible(fn(?MaintenanceRequests $record) => $record?->status === 'completed'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => Auth::user()->can('delete', MaintenanceRequests::class)),
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
                        return response()->streamDownload(fn() => print($mpdf->Output('', 'S')), 'maintenance-requests.pdf');
                    }),
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
        return [];
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




