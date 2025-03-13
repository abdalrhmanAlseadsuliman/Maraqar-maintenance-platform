<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceRequestsResource\Pages;
use App\Filament\Resources\MaintenanceRequestsResource\RelationManagers;
use App\Models\MaintenanceRequests;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;


class MaintenanceRequestsResource extends Resource
{
    protected static ?string $model = MaintenanceRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('property_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('request_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
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
                    ->maxLength(255),
                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->prefix('$'),
                    Repeater::make('images')
                    ->relationship('images') 
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('تحميل الصورة')
                            ->image() // يحدد أنه ملف صورة فقط
                            ->directory('maintenance-requests') // تخزين الملفات داخل مجلد مخصص
                            ->required(),
                    ]),
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
                    Tables\Columns\ImageColumn::make('image_path')
    ->label('الصورة')
    ->disk('public') // التأكد من أن الصور تُحمل من disk الصحيح
    ->width(100) // تعيين عرض الصورة
    ->height(100) // تعيين ارتفاع الصورة
    ,
                   
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\ImagesRelationManager::class,
        ];
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
