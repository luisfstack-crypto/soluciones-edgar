<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\OrderResource\Pages;
use App\Filament\Dashboard\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Mis Pedidos';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Mis Pedidos';
    protected static ?string $navigationGroup = 'Gestión';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function canCreate(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only view if viewed
                 Forms\Components\Section::make('Detalles del Pedido')
                    ->schema([
                        Forms\Components\TextInput::make('service.name')
                            ->label('Servicio'),
                        Forms\Components\TextInput::make('status')
                            ->label('Estado')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Pendiente',
                                'processing' => 'En Proceso',
                                'completed' => 'Completado',
                                'rejected' => 'Rechazado',
                                default => $state,
                            }),
                        Forms\Components\Textarea::make('admin_notes')
                             ->label('Notas del Administrador')
                             ->visible(fn ($record) => $record && $record->admin_notes),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price_at_purchase')
                    ->label('Costo')
                    ->money('MXN'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'processing' => 'En Proceso',
                        'completed' => 'Completado',
                        'rejected' => 'Rechazado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('manage')
                    ->label('Administrar')
                    ->icon('heroicon-m-pencil-square')
                    ->color('gray')
                    ->url(fn (Service $record) => "/admin/services/{$record->id}/edit")
                    ->visible(fn () => auth()->user()->is_admin),

                Tables\Actions\Action::make('download')
                    ->label('Descargar PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Order $record) => \Illuminate\Support\Facades\Storage::url($record->result_file_path))
                    ->openUrlInNewTab()
                    ->visible(fn (Order $record) => $record->status === 'completed' && $record->result_file_path),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
