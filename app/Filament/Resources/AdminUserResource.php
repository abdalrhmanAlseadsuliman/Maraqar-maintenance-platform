<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Enums\UserRole;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AdminUserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?int $navigationSort = 1;

    public static function getPluralLabel(): string
    {
        return 'الإدارة والموظفين';
    }

    public static function getNavigationLabel(): string
    {
        return 'إدارة الموظفين';
    }

    public static function getModelLabel(): string
    {
        return 'موظف';
    }

    // تطبيق فلتر لعرض الإدارة والموظفين فقط (استبعاد العملاء)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', '!=', UserRole::CLIENT);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('الايميل')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('national_id')
                    ->label('السجل المدني')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('phone')
                    ->label('الهاتف')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->default('0994423464'),
                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('تاكيد الايميل'),
                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label('المدينة')
                    ->required()
                    ->maxLength(255)
                    ->default('homs'),
                Forms\Components\Select::make('role')
                    ->label('مهام المستخدم')
                    ->placeholder('اختر مهام المستخدم')
                    ->options([
                        UserRole::CHAIRMAN => 'رئيس مجلس الإدارة',
                        UserRole::EXECDIR => 'المدير التنفيذي',
                        UserRole::ADMIN => 'مدير النظام',
                        UserRole::ACCOUNTANT => 'محاسب',
                        UserRole::MAINTTECH => 'فني صيانة',
                    ])
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('الايميل')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('الهاتف')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('المنصب')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        UserRole::CHAIRMAN => 'danger',
                        UserRole::EXECDIR => 'warning',
                        UserRole::ADMIN => 'success',
                        UserRole::ACCOUNTANT => 'info',
                        UserRole::MAINTTECH => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => UserRole::label($state) ?? $state),
                Tables\Columns\TextColumn::make('city')
                    ->label('المدينة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('تاكيد الايميل')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('المنصب')
                    ->options([
                        UserRole::CHAIRMAN => 'رئيس مجلس الإدارة',
                        UserRole::EXECDIR => 'المدير التنفيذي',
                        UserRole::ADMIN => 'مدير النظام',
                        UserRole::ACCOUNTANT => 'محاسب',
                        UserRole::MAINTTECH => 'فني صيانة',
                    ]),
                Tables\Filters\SelectFilter::make('city')
                    ->label('المدينة')
                    ->options([
                        'homs' => 'حمص',
                        'damascus' => 'دمشق',
                        'aleppo' => 'حلب',
                        'lattakia' => 'اللاذقية',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد'),
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
            'index' => Pages\ListAdminUsers::route('/'),
            'create' => Pages\CreateAdminUser::route('/create'),
            'view' => Pages\ViewAdminUser::route('/{record}'),
            'edit' => Pages\EditAdminUser::route('/{record}/edit'),
        ];
    }
}
