<?php

namespace App\Filament\Resources\MaintenanceRequestsResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\MaintenanceRequests;
use App\Models\MaintenanceRequestImages;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('image_path') 
                    ->label('رابط الصورة') 
                    ->placeholder('أدخل رابط الصورة هنا') 
                    ->required(),
                  
            ]);
    }
    
    
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('image_path') 
            ->columns([
                Tables\Columns\TextColumn::make('image_path') 
                    // ->disk('public') 
                    // ->circular(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
}
