<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Catálogo de Servicios';
    protected static ?string $modelLabel = 'Servicio';
    protected static ?string $pluralModelLabel = 'Servicios';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Principal')
                    ->description('Detalles básicos del servicio')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->label('Código')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre del Servicio')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('category_id')
                                    ->label('Categoría / Etiqueta')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nombre')
                                            ->required(),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Descripción'),
                                    ]),
                                Forms\Components\TextInput::make('service_type')
                                    ->label('Tipo de Servicio Interno (Legacy)')
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Precios y Tiempos')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Precio al Público')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$'),
                                Forms\Components\TextInput::make('cost')
                                    ->label('Costo Interno (Gasto)')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$'),
                                Forms\Components\TextInput::make('processing_time')
                                    ->label('Tiempo de Procesamiento')
                                    ->placeholder('Ej: 1-2 horas')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('active_schedule')
                                    ->label('Horario Activo')
                                    ->default('8:00 AM a 8:00 PM')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

                Forms\Components\Section::make('Multimedia y Estado')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Imagen del Servicio')
                            ->image()
                            ->directory('services')
                            ->disk(config('filesystems.default')) 
                            ->visibility('public')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Campos Personalizados (Formulario)')
                    ->description('Agrega campos extra que el usuario debe llenar al solicitar este servicio.')
                    ->schema([
                        Forms\Components\Repeater::make('form_schema')
                            ->label('Configuración de Campos Extra')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Identificador (sin espacios)')
                                    ->required()
                                    ->regex('/^[a-z0-9_]+$/')
                                    ->rules(['alpha_dash']),
                                Forms\Components\TextInput::make('label')
                                    ->label('Etiqueta (visible al usuario)')
                                    ->required(),
                                Forms\Components\Toggle::make('required')
                                    ->label('¿Obligatorio?')
                                    ->default(true),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Agregar Campo Personalizado')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('image_path')
                        ->disk(config('filesystems.default')) 
                        ->height(200)
                        ->width('100%')
                        ->alignCenter()
                        ->extraImgAttributes(['class' => 'object-contain object-center mx-auto block bg-gray-50 rounded']),
                    Tables\Columns\TextColumn::make('code')
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Medium)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold)
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                        ->searchable(),
                    Tables\Columns\TextColumn::make('category.name')
                        ->label('Categoría / Etiqueta')
                        ->badge()
                        ->color('info'),
                    Tables\Columns\TextColumn::make('description')
                        ->color('gray')
                        ->limit(100),

                    Tables\Columns\TextColumn::make('active_schedule')
                        ->formatStateUsing(fn ($state) => "SERVICIO ACTIVO: " . $state)
                        ->icon('heroicon-m-clock')
                        ->color('warning')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Small),

                    Tables\Columns\TextColumn::make('processing_time')
                         ->prefix('Tiempo estimado: ')
                         ->color('gray')
                         ->size(Tables\Columns\TextColumn\TextColumnSize::Small),

                    Tables\Columns\TextColumn::make('pending')
                         ->counts('orders', fn (Builder $query) => $query->whereIn('status', ['pending', 'processing']))
                         ->label('Atención Requerida')
                         ->badge()
                         ->icon(fn ($state) => $state > 0 ? 'heroicon-m-bell-alert' : 'heroicon-m-check-circle')
                         ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                         ->formatStateUsing(fn ($state) => $state > 0 ? "{$state} Pendientes" : "Sin pendientes")
                         ->extraAttributes(fn ($state) => $state > 0 ? ['class' => 'animate-pulse font-bold'] : []),

                    Tables\Columns\TextColumn::make('price')
                        ->money('MXN')
                        ->weight(FontWeight::Bold)
                        ->color('success')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Large),
                ])->space(3),
            ])
            ->groups([
                Tables\Grouping\Group::make('category.name')
                    ->label('Categoría')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->defaultGroup('category.name')
            ->filters([
            ])
            ->actions([
                Tables\Actions\Action::make('solicitar')
                    ->label('SOLICITAR')
                    ->button()
                    ->color('primary')
                    ->url(fn (Service $record) => route('filament.dashboard.pages.buy-service', ['service' => $record->id]))
                    ->visible(fn (Service $record) => $record->is_active),
                    
                Tables\Actions\EditAction::make(),
            ])
            
            ->defaultPaginationPageOption(50)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}