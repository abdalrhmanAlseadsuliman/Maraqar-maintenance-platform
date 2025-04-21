<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Mpdf\Mpdf;
use Filament\Tables;
use Filament\Actions;
use App\Enums\RequestType;
use Filament\Tables\Table;
use App\Models\MaintenanceRequests;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;


use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\MaintenanceRequestsResource;


class ListMaintenanceRequests extends ListRecords
{
    protected static string $resource = MaintenanceRequestsResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTitle(): string
    {
        return ' إدارة طلبات الصيانة';
    }

    public function getBreadcrumb(): string
    {
        return 'قائمة الطلبات';
    }
    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('property_id')->numeric()->sortable(),
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
            TextColumn::make('submitted_at')->dateTime()->sortable(),
            TextColumn::make('technician_name')->searchable(),
            // TextColumn::make('executive_director_notes')->searchable(),
            TextColumn::make('cost')->money()->sortable(),

            ImageColumn::make('images')
                ->label('الصور')
                ->disk('public')
                ->width(80)
                ->height(80)
                ->getStateUsing(fn($record) => optional($record->images->first())->image_path ? asset('storage/' . $record->images->first()->image_path) : null),
        ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Action::make('exportPdf')
                    ->label('تصدير PDF')
                    ->color('primary')
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
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => Auth::user()->can('delete', MaintenanceRequests::class)),
                    // ->visible(fn () => Auth::user()?->can('delete', MaintenanceRequests::first()))

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
}
