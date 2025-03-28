<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->live(),
                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->live(),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Encrypt password
                    ->hiddenOn('edit'),
                TextInput::make('address')->maxLength(255),
                TextInput::make('city')->maxLength(255),
                TextInput::make('country')->maxLength(255),
                TextInput::make('postcode')
                    ->maxLength(20)
                    ->dehydrateStateUsing(fn ($state) => str_replace(' ', '', $state)) // Remove all spaces
                    ->live(),
                Select::make('agencies')
                    ->relationship('agencies', 'name') // Defines the many-to-many relation
                    ->multiple() // Allows multiple agencies
                    ->searchable() // Enables search in the dropdown
                    ->preload(), // Preloads options for a better UX
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('address')->sortable()->sortable(),
                TextColumn::make('city')->sortable()->sortable(),
                TextColumn::make('country')->sortable()->sortable(),
                TextColumn::make('postcode')->sortable()->sortable(),
                TextColumn::make('agencies.name')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
