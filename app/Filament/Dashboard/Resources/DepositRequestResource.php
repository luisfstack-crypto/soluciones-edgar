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
    protected static ?string $navigationLabel = 'Abonar Saldo';
    protected static ?string $modelLabel = 'Solicitud de Depósito';
    protected static ?string $pluralModelLabel = 'Solicitudes de Depósito';
    protected static ?string $navigationGroup = 'Mi Billetera';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Bancaria')
                    ->description('Realiza tu transferencia a la siguiente cuenta y sube tu comprobante.')
                    ->schema([
                        Forms\Components\Placeholder::make('bank_details')
                            ->label('')
                            ->content(new \Illuminate\Support\HtmlString('
                                <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-100/50 border border-gray-200 dark:border-gray-700 text-center">
                                    <div class="flex justify-center">
                                        <img src="'.asset('images/pays.jpeg').'" alt="Instrucciones de Pago" class="mx-auto max-h-24 object-contain" />
                                    </div>
                                    <div class="mt-4 p-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded text-xs text-amber-800 dark:text-amber-400">
                                        <strong>Nota:</strong> Las recargas se procesan de lunes a domingo de 8:00 AM a 8:00 PM. Monto mínimo $300.00 MXN.
                                    </div>
                                </div>
                            ')),
                    ]),

                Forms\Components\Section::make('Registrar Comprobante')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id()),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('bank_name')
                                    ->label('Tu Banco')
                                    ->placeholder('Ej: BBVA, Santander, OXXO...')
                                    ->required(),

                                Forms\Components\TextInput::make('tracking_key')
                                    ->label('Clave de Rastreo / Referencia')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Monto Enviado')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->minValue(300)
                                    ->rule('min:300'),

                                Forms\Components\FileUpload::make('proof_image_path')
                                    ->label('Captura del Comprobante')
                                    ->directory('deposit-proofs')
                                    ->image()
                                    ->required(),
                            ]),
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
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Banco Emisor'),
                Tables\Columns\TextColumn::make('tracking_key')
                    ->label('Referencia/Rastreo'),
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
