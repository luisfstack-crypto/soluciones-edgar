<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\TransactionResource\Pages;
use App\Filament\Dashboard\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Historial';
    protected static ?string $modelLabel = 'Transacción';
    protected static ?string $pluralModelLabel = 'Historial de Transacciones';
    protected static ?string $navigationGroup = 'Billetera';

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
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'deposit' => 'Depósito',
                        'purchase' => 'Pago',
                        'refund' => 'Reembolso',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'deposit' => 'success',
                        'purchase' => 'gray', // Dashboard uses gray for purchase? Let's use danger if amount < 0 logic holds, or follow specific logic. Admin uses gray (purchase) and success (deposit/refund). Dashboard uses gray? 
                        // Wait, Admin used: 'deposit' => 'success', 'purchase' => 'danger', 'refund' => 'warning' in Step 436.
                        // Dashboard used: 'deposit' => 'success', 'purchase' => 'gray', 'refund' => 'success' in Step 435.
                        // I will align to Admin logic as it's clearer.
                        'deposit' => 'success',
                        'purchase' => 'danger',
                        'refund' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->wrap()
                    ->limit(50),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('MXN')
                    ->color(fn ($record) => $record->type === 'purchase' ? 'danger' : 'success')
                    ->weight('bold'),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
