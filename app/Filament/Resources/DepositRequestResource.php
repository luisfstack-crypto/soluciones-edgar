<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepositRequestResource\Pages;
use App\Models\DepositRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class DepositRequestResource extends Resource
{
    protected static ?string $model = DepositRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Solicitudes de Saldo';
    protected static ?string $modelLabel = 'Solicitud de Depósito';
    protected static ?string $pluralModelLabel = 'Solicitudes de Depósito';
    protected static ?string $navigationGroup = 'Finanzas';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Instrucciones de Depósito')
                    ->description('Por favor lea atentamente antes de continuar.')
                    ->schema([
                        Forms\Components\Placeholder::make('instructions')
                            ->hiddenLabel()
                            ->content(new \Illuminate\Support\HtmlString('
                                <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
                                    <h3 class="font-bold text-lg mb-2">IMPORTANTE</h3>
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Monto Mínimo: <strong>$300.00 MXN</strong>. Envío menor causará pérdida de saldo.</li>
                                        <li><strong>SOLO TRANSFERENCIAS ELECTRÓNICAS.</strong> No aceptamos depósitos en efectivo.</li>
                                        <li>Debe registrar el abono el mismo día que realizó la transferencia.</li>
                                    </ul>
                                    <div class="mt-4 p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
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
                    ->height(50)
                    ->openUrlInNewTab(),
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
                    ->label('Aprobar y Abonar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('¿Aprobar Depósito?')
                    ->modalDescription('Esta acción agregará el saldo inmediatamente a la cuenta del usuario. No se puede deshacer.')
                    ->visible(fn (DepositRequest $record) => $record->status === 'pending' && auth()->user()->is_admin)
                    ->action(function (DepositRequest $record) {
                        DB::transaction(function () use ($record) {
                            $freshRecord = DepositRequest::where('id', $record->id)->lockForUpdate()->first();

                            if ($freshRecord->status !== 'pending') {
                                Notification::make()
                                    ->title('Error')
                                    ->body('Esta solicitud ya fue procesada anteriormente.')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            if (method_exists($freshRecord->user, 'addBalance')) {
                                $freshRecord->user->addBalance(
                                    $freshRecord->amount, 
                                    'deposit', 
                                    "Recarga Aprobada (Ref: {$freshRecord->tracking_key})", 
                                    $freshRecord
                                );
                            } else {
                                $freshRecord->user->balance += $freshRecord->amount;
                                $freshRecord->user->save();
                            }
                            
                            $freshRecord->update(['status' => 'approved']);
                            
                            Notification::make()
                                ->title('Depósito Aprobado')
                                ->success()
                                ->send();
                        });
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
                        
                        Notification::make()
                            ->title('Depósito Rechazado')
                            ->danger()
                            ->send();
                    }),
                    
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->is_admin)
                        ->action(function (Collection $records) {
                            $deletedCount = 0;
                            foreach ($records as $record) {
                                if ($record->status !== 'approved') {
                                    $record->delete();
                                    $deletedCount++;
                                }
                            }
                            
                            if ($deletedCount < $records->count()) {
                                Notification::make()
                                    ->title('Atención')
                                    ->body('Algunos registros no se borraron porque ya estaban Aprobados. Solo se eliminaron pendientes/rechazados.')
                                    ->warning()
                                    ->send();
                            } else {
                                Notification::make()->title('Registros eliminados')->success()->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        } else {
             $query->withoutGlobalScopes();
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