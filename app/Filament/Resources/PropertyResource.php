<?php


namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Property;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PropertyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PropertyResource\RelationManagers;



class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $panel = 'admin';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';


    public static function getPluralLabel(): string
    {
        return ' العقارات' ;
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
                // ->visible(fn () => auth()->user()->hasRole(['admin']))
                ->disabled(fn () => auth()->user()->hasRole('CLT'))
                ->required()
                ->maxLength(255),
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
            // ->query(fn (Builder $query) => $query->where('user_id', '1'))

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
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => Auth::user()->can('delete', Property::class)),
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

//     public static function canDelete(Model $record): bool
// {
//     return auth()->user()->can('delete', $record);
// }

}
