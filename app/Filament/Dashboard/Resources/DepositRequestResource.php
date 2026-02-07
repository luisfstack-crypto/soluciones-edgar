<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\DepositRequestResource\Pages;
use App\Filament\Dashboard\Resources\DepositRequestResource\RelationManagers;
use App\Models\DepositRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepositRequestResource extends Resource
{
    protected static ?string $model = DepositRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Recargar Saldo';
    protected static ?string $modelLabel = 'Solicitud de Depósito';
    protected static ?string $pluralModelLabel = 'Solicitudes de Depósito';
    protected static ?string $navigationGroup = 'Billetera';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
                Forms\Components\Section::make('Detalles del Depósito')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->label('Método de Pago')
                            ->options([
                                'bank_transfer' => 'Transferencia Bancaria',
                                'cash_deposit' => 'Depósito en Efectivo',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Monto')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Referencia / Folio')
                            ->required(),
                        Forms\Components\FileUpload::make('proof_file_path')
                            ->label('Comprobante (Imagen/PDF)')
                            ->directory('deposit-proofs')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('MXN'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Método')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bank_transfer' => 'Transferencia',
                        'cash_deposit' => 'Depósito',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                        default => $state,
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepositRequests::route('/'),
            'create' => Pages\CreateDepositRequest::route('/create'),
        ];
    }
}
