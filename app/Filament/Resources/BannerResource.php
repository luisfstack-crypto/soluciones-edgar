<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Avisos y Publicidad';
    protected static ?string $modelLabel = 'Aviso';
    protected static ?string $pluralModelLabel = 'Avisos y Publicidad';
    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Título (Interno o Principal)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('Tipo de Aviso')
                    ->options([
                        'info' => 'Informativo (Azul)',
                        'success' => 'Éxito / Positivo (Verde)',
                        'warning' => 'Advertencia (Amarillo)',
                        'danger' => 'Alerta Crítica (Rojo)',
                    ])
                    ->required()
                    ->default('info'),
                Forms\Components\RichEditor::make('content')
                    ->label('Contenido del Aviso')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Activo (Visible para los usuarios)')
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Orden (Menor número = Más arriba)')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'info' => 'info',
                        'success' => 'success',
                        'warning' => 'warning',
                        'danger' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Activo'),
                Tables\Columns\TextInputColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                //
            ])
            ->actions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
