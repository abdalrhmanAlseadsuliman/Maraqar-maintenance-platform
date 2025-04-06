<?php

namespace App\Filament\User\Resources;

use Mpdf\Mpdf;
use Filament\Tables;
use App\Models\Property;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\MaintenanceRequests;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use App\Filament\User\Resources\MaintenanceRequestsResource\Pages;

class MaintenanceRequestsResource extends Resource
{
    protected static ?string $model = MaintenanceRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'طلبات الصيانة';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('property_id')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('request_type')->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('submitted_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('technician_visits')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('technician_name')->searchable(),
                Tables\Columns\TextColumn::make('cost')->money()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
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
                        return response()->streamDownload(fn () => print($mpdf->Output('', 'S')), 'maintenance-requests.pdf');
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
