<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Filament\Resources\AgencyResource\RelationManagers;
use App\Models\Agency;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgencyResource extends Resource
{
    protected static ?string $model = Agency::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('address')
                    ->maxLength(255),
                TextInput::make('city')
                    ->maxLength(255),
                TextInput::make('country')
                    ->maxLength(255),
                TextInput::make('postcode')
                    ->maxLength(20)
                    ->dehydrateStateUsing(fn ($state) => str_replace(' ', '', $state)) // Remove all spaces
                    ->live(),
                TextInput::make('number_of_users')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(1000)
                    ->required(),
                DatePicker::make('date_of_information')
                    ->required()
                    ->native(false)
                    ->minDate('1975-01-01')
                    ->maxDate(now()),
                Select::make('business_type')
                    ->options([
                        'UK' => 'UK',
                        'Europe' => 'Europe',
                    ])
                    ->required(),
                TextInput::make('company_number')
                    ->required()
                    ->label('Company Number')
                    ->maxLength(14)
                    ->rule(fn (callable $get) => match ($get('business_type')) {
                        'UK' => 'regex:/^\d{11}$/', // ✅ Must be 11 digits for UK
                        'Europe' => 'regex:/^[A-Za-z0-9]{14}$/', // ✅ Must be 14 alphanumeric for Europe
                        default => '',
                    })
                    ->helperText('UK: 11 digits | Europe: 14 alphanumeric'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Select::make('users')
                    ->relationship('users', 'first_name') // Defines the many-to-many relation
                    ->multiple() // Allows multiple users
                    ->searchable() // Enables search in the dropdown
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('address')->sortable(),
                TextColumn::make('city')->sortable(),
                TextColumn::make('country')->sortable(),
                TextColumn::make('postcode')->sortable(),
                TextColumn::make('number_of_users')->sortable(),
                TextColumn::make('date_of_information')->date()->sortable(),
                TextColumn::make('business_type')->sortable(),
                TextColumn::make('company_number')->sortable(),
                TextColumn::make('users.first_name')
                ->badge(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgencies::route('/'),
            'create' => Pages\CreateAgency::route('/create'),
            'edit' => Pages\EditAgency::route('/{record}/edit'),
        ];
    }
}
