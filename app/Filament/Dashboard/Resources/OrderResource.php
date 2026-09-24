<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Mis Trámites';
    protected static ?string $modelLabel = 'Trámite';
    protected static ?string $pluralModelLabel = 'Mis Trámites';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 2;

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
                 Forms\Components\Section::make('Detalles del Pedido')
                    ->schema([
                        Forms\Components\TextInput::make('service.name')
                            ->label('Servicio')
                            ->disabled(),
                        Forms\Components\TextInput::make('status')
                            ->label('Estado')
                            ->disabled()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Pendiente',
                                'processing' => 'En Proceso',
                                'completed' => 'Completado',
                                'rejected' => 'Rechazado',
                                default => $state,
                            }),
                        Forms\Components\Textarea::make('admin_notes')
                             ->label('Notas del Administrador')
                             ->rows(4)
                             ->disabled()
                             ->visible(fn ($record) => $record && $record->admin_notes),
                    ])
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // ── Section 1: Status badge ───────────────────────────────────
                InfoSection::make()
                    ->schema([
                        TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending'    => 'gray',
                                'processing' => 'info',
                                'completed'  => 'success',
                                'rejected'   => 'danger',
                                default      => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending'    => 'Pendiente',
                                'processing' => 'En Proceso',
                                'completed'  => 'Completado',
                                'rejected'   => 'Rechazado',
                                default      => $state,
                            }),
                    ]),

                // ── Section 2: Order details ──────────────────────────────────
                InfoSection::make('Información del Pedido')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('service.name')
                            ->label('SERVICIO'),

                        TextEntry::make('price_at_purchase')
                            ->label('PRECIO')
                            ->money('MXN'),

                        TextEntry::make('created_at')
                            ->label('FECHA DE PEDIDO')
                            ->formatStateUsing(
                                fn ($state) => $state
                                    ? \Carbon\Carbon::parse($state)->locale('es')->translatedFormat('d \d\e F \d\e Y \a \l\a\s h:i a')
                                    : '—'
                            ),

                        TextEntry::make('updated_at')
                            ->label('FECHA DE ENTREGA')
                            ->formatStateUsing(
                                fn ($state) => $state
                                    ? \Carbon\Carbon::parse($state)->locale('es')->translatedFormat('d \d\e F \d\e Y \a \l\a\s h:i a')
                                    : '—'
                            )
                            ->visible(fn ($record) => $record && $record->status === 'completed'),

                        TextEntry::make('input_data')
                            ->label(fn ($record) => ($record?->service?->code === 'recibo-cfe')
                                ? 'NÚMERO DE SERVICIO CFE'
                                : 'DATOS ADICIONALES'
                            )
                            ->columnSpanFull()
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return new HtmlString('<span class="text-gray-400 italic">Sin datos</span>');
                                }
                                $data = is_array($state) ? $state : (json_decode($state, true) ?? []);
                                $rows = collect($data)
                                    ->map(fn ($value, $key) =>
                                        '<div class="flex gap-2">'
                                        . '<span class="font-semibold capitalize text-gray-600 dark:text-gray-300">' . e(str_replace('_', ' ', $key)) . ':</span>'
                                        . '<span class="text-gray-800 dark:text-white">' . e($value) . '</span>'
                                        . '</div>'
                                    )
                                    ->implode('');
                                return new HtmlString('<div class="space-y-1">' . $rows . '</div>');
                            }),
                    ]),

                // ── Section 3: Document banner (completed only) ───────────────
                InfoSection::make()
                    ->schema([
                        TextEntry::make('result_file_path')
                            ->hiddenLabel()
                            ->formatStateUsing(function ($state, $record) {
                                return new HtmlString('
                                    <div class="flex items-center gap-4 rounded-xl border border-green-200 bg-green-50 px-5 py-4 dark:border-green-800 dark:bg-green-950/30">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-green-600 dark:text-green-400">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-green-800 dark:text-green-200">Documento disponible</p>
                                            <p class="text-xs text-green-600 dark:text-green-400">Puedes descargarlo desde el botón \'Descargar\'</p>
                                        </div>
                                    </div>
                                ');
                            }),
                    ])
                    ->visible(fn ($record) => $record && $record->status === 'completed' && $record->result_file_path),
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
                    })
                    ->description(fn (Order $record): string => (string) ($record->admin_notes ?? '')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\OrderExporter::class)
                    ->label('Exportar mis trámites')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->formats([
                        \Filament\Actions\Exports\Enums\ExportFormat::Xlsx,
                        \Filament\Actions\Exports\Enums\ExportFormat::Csv,
                    ]),
            ])
            ->actions([
                // ── Detalle ───────────────────────────────────────────────────
                ViewAction::make('view')
                    ->label('Detalle')
                    ->color('gray')
                    ->modalHeading('Detalle de Orden'),

                // ── Descargar ─────────────────────────────────────────────────
                Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn (Order $record) => route('orders.download', ['order' => $record->id]))
                    ->openUrlInNewTab()
                    ->visible(fn (Order $record) => $record->status === 'completed' && $record->result_file_path),

                // ── Compartir ─────────────────────────────────────────────────
                Action::make('share')
                    ->label('Compartir')
                    ->icon('heroicon-o-share')
                    ->color('success')
                    ->extraAttributes(function (Order $record) {
                        $downloadUrl = route('orders.download', ['order' => $record->id]);
                        $title       = 'Mi trámite #' . $record->id;
                        $text        = 'Mira mi documento del trámite: ' . ($record->service?->name ?? 'Trámite');
                        $whatsappUrl = 'https://api.whatsapp.com/send?text=' . urlencode($text . ' ' . $downloadUrl);

                        return [
                            '@click.prevent' => "
                                if (navigator.share) {
                                    navigator.share({
                                        title: " . json_encode($title) . ",
                                        text:  " . json_encode($text) . ",
                                        url:   " . json_encode($downloadUrl) . ",
                                    }).catch(() => {});
                                } else {
                                    window.open(" . json_encode($whatsappUrl) . ", '_blank');
                                }
                            ",
                        ];
                    })
                    ->action(fn () => null)
                    ->visible(fn (Order $record) => $record->status === 'completed' && $record->result_file_path),

                // ── Admin shortcut ────────────────────────────────────────────
                Action::make('manage')
                    ->label('Administrar')
                    ->icon('heroicon-m-pencil-square')
                    ->color('gray')
                    ->url(fn (Order $record) => "/admin/orders/{$record->id}/edit")
                    ->visible(fn () => auth()->user()->is_admin),
            ])
            ->bulkActions([])
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
        ];
    }
}
