<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Pedidos';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Pedidos';
    protected static ?string $navigationGroup = 'Gestión Operativa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Pedido')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Usuario')
                                    ->required()
                                    ->searchable(),
                                Forms\Components\Select::make('service_id')
                                    ->relationship('service', 'name')
                                    ->label('Servicio')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('input_data', [])),
                            ]),
                        
                        Forms\Components\Group::make()
                            ->schema(function (Forms\Get $get) {
                                $serviceId = $get('service_id');
                                if (! $serviceId) {
                                    return [];
                                }
                                $service = \App\Models\Service::find($serviceId);
                                if (! $service || ! $service->form_schema) {
                                     return [
                                        Forms\Components\TextInput::make('input_data.text')
                                            ->label('Detalles adicionales')
                                            ->required(),
                                    ];
                                }
                                
                                return collect($service->form_schema)->map(function ($field) {
                                    $input = Forms\Components\TextInput::make("input_data.{$field['name']}")
                                        ->label($field['label'])
                                        ->required($field['required'] ?? false);
        
                                    if (isset($field['regex'])) {
                                        $input->regex($field['regex']);
                                    }
        
                                    return $input;
                                })->toArray();
                            }),
                    ]),

                Forms\Components\Section::make('Estado y Entrega')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'processing' => 'En Proceso',
                                'completed' => 'Completado',
                                'rejected' => 'Rechazado',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        Forms\Components\FileUpload::make('result_file_path')
                            ->label('Archivo Resultado (PDF)')
                            ->directory('order-results')
                            ->acceptedFileTypes(['application/pdf'])
                            ->downloadable()
                            ->openable(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notas del Admin')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_at_purchase')
                    ->label('Costo')
                    ->money('MXN')
                    ->sortable(),
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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'processing' => 'En Proceso',
                        'completed' => 'Completado',
                        'rejected' => 'Rechazado',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('upload_result')
                    ->label('Subir Resultado')
                    ->icon('heroicon-m-arrow-up-tray')
                    ->form([
                        FileUpload::make('result_file_path')
                            ->label('Archivo PDF')
                            ->required()
                            ->directory('order-results')
                            ->acceptedFileTypes(['application/pdf']),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notas'),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update([
                            'result_file_path' => $data['result_file_path'],
                            'admin_notes' => $data['admin_notes'] ?? $record->admin_notes,
                            'status' => 'completed',
                        ]);
                    })
                    ->visible(fn (Order $record) => $record->status !== 'completed'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
