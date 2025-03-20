<?php

namespace App\Filament\User\Resources;

use auth;
use Filament\Forms;
use Filament\Tables;
use App\Models\Property;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\User\Resources\PropertyResource\Pages;
use App\Filament\User\Resources\PropertyResource\RelationManagers;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 1;
    // protected static ?string $navigationLabel = 'العقارات';

    public static function getPluralLabel(): string
    {
        return 'العقارات';
    }

    public static function getModelLabel(): string
    {
        return 'عقار جديد';
    }

    public static function getNavigationLabel(): string
    {
        return 'إدارة العقارات';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('owner', 'name')
                    ->label('مالك العقار')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('اسم العقار')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('property_number')
                    ->label('رقم العقار')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title_deed_number')
                    ->label('رقم صك الملكية')
                    ->required()
                    ->maxLength(255)
                    ->visible(fn () => auth()->user()->hasRole(['CLT'])),
                Forms\Components\TextInput::make('land_piece_number')
                    ->label('رقم الارض')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('plan_number')
                    ->label('رقم المخطط')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('sale_date')
                    ->label('تاريخ الشراء')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('مالك العقار')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم العقار')
                    ->searchable(),
                Tables\Columns\TextColumn::make('property_number')
                    ->label('رقم العقار')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_deed_number')
                    ->label('رقم صك الملكية')
                    ->searchable(),
                Tables\Columns\TextColumn::make('land_piece_number')
                    ->label('رقم الارض')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan_number')
                    ->label('رقم المخطط')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sale_date')
                    ->label('تاريخ الشراء')
                    ->date()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make()->label('إضافة عقار جديد'),
            // ])

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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('owner', function ($query) {
            $query->where('user_id', auth()->id()); // 🔹 تصفية الطلبات بناءً على مالك العقار
        });
    }
}
