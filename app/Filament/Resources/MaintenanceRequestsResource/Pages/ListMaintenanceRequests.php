<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

use Mpdf\Mpdf;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions;


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
            TextColumn::make('request_type')->searchable(),
            TextColumn::make('status'),
            TextColumn::make('submitted_at')->dateTime()->sortable(),
            TextColumn::make('technician_name')->searchable(),
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
}


// namespace App\Filament\Resources\MaintenanceRequestsResource\Pages;

// use App\Filament\Resources\MaintenanceRequestsResource;
// use Filament\Actions;
// use Filament\Resources\Pages\ListRecords;

// class ListMaintenanceRequests extends ListRecords
// {
//     protected static string $resource = MaintenanceRequestsResource::class;

//     public function getTitle(): string
//     {
//         return ' إدارة طلبات الصيانة';
//     }

//     public function getBreadcrumb(): string
//     {
//         return 'قائمة الطلبات';
//     }
//     protected function getHeaderActions(): array
//     {
//         return [
//             Actions\CreateAction::make()->label(' إنشاء طلب جديد'),
//         ];
//     }
// }
