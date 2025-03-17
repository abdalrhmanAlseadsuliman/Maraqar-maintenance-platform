<?php


namespace App\Filament\Resources;
// namespace App\Filament\Resources;

// use Filament\Forms;
// use Filament\Tables;
// use App\Models\Property;
// use Filament\Forms\Form;
// use Filament\Tables\Table;
// use Filament\Resources\Resource;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Database\Eloquent\Builder;
// use App\Filament\Resources\PropertyResource\Pages;
// use Illuminate\Database\Eloquent\SoftDeletingScope;
// use App\Filament\Resources\PropertyResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Property;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PropertyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PropertyResource\RelationManagers;



class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $panel = 'admin';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('owner' , 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('property_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('property_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title_deed_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('land_piece_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('plan_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('sale_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->query(fn (Builder $query) => $query->where('user_id', '1'))

            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('property_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_deed_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('land_piece_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sale_date')
                    ->date()
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
            //
        ];
    }


    // public static function getEloquentQuery(): Builder
    // {
    //     // dump(  Auth::user()->name ); // للتحقق من ID المستخدم الحالي
    //     return parent::getEloquentQuery()->whereHas('owner', function ($query) {
    //         $query->where('id', Auth::user()->id);
    //     });
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'view' => Pages\ViewProperty::route('/{record}'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
