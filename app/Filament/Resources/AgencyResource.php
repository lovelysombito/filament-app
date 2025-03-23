<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgencyResource\Pages;
use App\Filament\Resources\AgencyResource\RelationManagers;
use App\Models\Agency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->maxLength(255),
                Forms\Components\TextInput::make('postcode')
                    ->maxLength(20)
                    ->dehydrateStateUsing(fn ($state) => str_replace(' ', '', $state)) // Remove all spaces
                    ->live(),
                Forms\Components\TextInput::make('number_of_users')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(1000)
                    ->required(),
                Forms\Components\DatePicker::make('date_of_information')
                    ->required()
                    ->native(false)
                    ->minDate('1975-01-01')
                    ->maxDate(now()),
                Forms\Components\Select::make('business_type')
                    ->options([
                        'UK' => 'UK',
                        'Europe' => 'Europe',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('company_number')
                    ->required()
                    ->label('Company Number')
                    ->maxLength(14)
                    ->rule(fn (callable $get) => match ($get('business_type')) {
                        'UK' => 'regex:/^\d{11}$/', // ✅ Must be 11 digits for UK
                        'Europe' => 'regex:/^[A-Za-z0-9]{14}$/', // ✅ Must be 14 alphanumeric for Europe
                        default => '',
                    })
                    ->helperText('UK: 11 digits | Europe: 14 alphanumeric'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address')->sortable(),
                Tables\Columns\TextColumn::make('city')->sortable(),
                Tables\Columns\TextColumn::make('country')->sortable(),
                Tables\Columns\TextColumn::make('postcode')->sortable(),
                Tables\Columns\TextColumn::make('number_of_users')->sortable(),
                Tables\Columns\TextColumn::make('date_of_information')->date()->sortable(),
                Tables\Columns\TextColumn::make('business_type')->sortable(),
                Tables\Columns\TextColumn::make('company_number')->sortable()
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
