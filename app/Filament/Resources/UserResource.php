<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Catálogo de Usuarios';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->maxLength(255),
                    Forms\Components\DateTimePicker::make('email_verified_at')
                        ->label('Verificado el'),
                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(20),
                ]),

                Forms\Components\Section::make('Información de Empresa')
                ->schema([
                    Forms\Components\Select::make('account_type')
                        ->label('Tipo de Cuenta')
                        ->options([
                            'personal' => 'Personal',
                            'company' => 'Empresa',
                        ])
                        ->required()
                        ->live(),
                    Forms\Components\TextInput::make('company_name')
                        ->label('Nombre de la Empresa')
                        ->visible(fn (Forms\Get $get) => $get('account_type') === 'company'),
                    Forms\Components\Toggle::make('is_verified_company')
                        ->label('Empresa Verificada')
                        ->visible(fn (Forms\Get $get) => $get('account_type') === 'company'),
                ]),

                Forms\Components\Section::make('Sistema')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('balance')
                        ->prefix('$')
                        ->required()
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_admin')
                        ->label('Es Administrador')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'personal' => 'info',
                        'company' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'personal' => 'Personal',
                        'company' => 'Empresa',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('is_verified_company')
                    ->label('Verificado')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Saldo')
                    ->money('MXN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_admin')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
