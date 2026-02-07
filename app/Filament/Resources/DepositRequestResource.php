<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepositRequestResource\Pages;
use App\Filament\Resources\DepositRequestResource\RelationManagers;
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
    protected static ?string $navigationLabel = 'Solicitudes de Saldo';
    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Instrucciones de Depósito')
                    ->description('Por favor lea atentamente antes de continuar.')
                    ->schema([
                        Forms\Components\Placeholder::make('instructions')
                            ->label('')
                            ->content(new \Illuminate\Support\HtmlString('
                                <div class="p-4 bg-danger-500/10 border border-danger-500 rounded-lg text-danger-600 dark:text-danger-400">
                                    <h3 class="font-bold text-lg mb-2">IMPORTANTE</h3>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Monto Mínimo: <strong>$300.00 MXN</strong>. Envío menor causará pérdida de saldo.</li>
                                        <li><strong>SOLO TRANSFERENCIAS ELECTRONICAS.</strong> No aceptamos depósitos en efectivo.</li>
                                        <li>Debe registrar el abono el mismo día que realizó la transferencia.</li>
                                    </ul>
                                    <div class="mt-4 p-2 bg-gray-100 dark:bg-gray-800 rounded">
                                        <p><strong>Cuenta:</strong> 072180012770965706</p>
                                        <p><strong>Banco:</strong> Banorte</p>
                                        <p><strong>Beneficiario:</strong> Soluciones Edgar</p>
                                    </div>
                                </div>
                            '))
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Detalles de la Transferencia')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id()),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('bank_name')
                                    ->label('Banco Emisor')
                                    ->placeholder('Ej: BBVA, Santander')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('tracking_key')
                                    ->label('Clave de Rastreo / Referencia')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),

                        Forms\Components\TextInput::make('amount')
                            ->label('Monto Enviado')
                            ->prefix('$')
                            ->numeric()
                            ->minValue(300)
                            ->required()
                            ->helperText('Mínimo $300.00 MXN'),

                        Forms\Components\FileUpload::make('proof_image_path')
                            ->label('Comprobante de Pago (Captura)')
                            ->image()
                            ->directory('deposit-proofs')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notas del Administrador')
                            ->visible(fn () => auth()->user()->is_admin)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Banco')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tracking_key')
                    ->label('Rastreo')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('MXN')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                Tables\Columns\ImageColumn::make('proof_image_path')
                    ->label('Comprobante')
                    ->height(50),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
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
                    ->options([
                        'pending' => 'Pendiente',
                        'approved' => 'Aprobado',
                        'rejected' => 'Rechazado',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (DepositRequest $record) => $record->status === 'pending' && auth()->user()->is_admin)
                    ->action(function (DepositRequest $record) {
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                            $record->user->addBalance(
                                $record->amount, 
                                'deposit', 
                                "Recarga Aprobada (Ref: {$record->tracking_key})", 
                                $record
                            );
                            
                            $record->update(['status' => 'approved']);
                        });
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Depósito Aprobado')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Rechazar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Motivo del rechazo')
                            ->required(),
                    ])
                    ->visible(fn (DepositRequest $record) => $record->status === 'pending' && auth()->user()->is_admin)
                    ->action(function (DepositRequest $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['reason']
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Depósito Rechazado')
                            ->danger()
                            ->send();
                    }),
                    
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([ // Only delete if pending? Or generic delete. I'll leave generic but maybe restricts
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->is_admin),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepositRequests::route('/'),
            'create' => Pages\CreateDepositRequest::route('/create'),
            'edit' => Pages\EditDepositRequest::route('/{record}/edit'),
        ];
    }
}
