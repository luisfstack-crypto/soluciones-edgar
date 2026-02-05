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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Servicios';
    protected static ?string $modelLabel = 'Servicio';
    protected static ?string $pluralModelLabel = 'Servicios';

    public static function form(Form $form): Form
    {
        return $form
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
                Forms\Components\TextInput::make('service_type')
                    ->label('Tipo de Servicio')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Imagen')
                    ->image()
                    ->directory('service-images'),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('active_schedule')
                    ->label('Horario Activo')
                    ->default('8:00 AM a 8:00 PM')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('processing_time')
                    ->label('Tiempo de Procesamiento')
                    ->placeholder('Ej: 1-2 horas')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
                    ->required(),
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
                        ->label('Imagen')
                        ->height('100%')
                        ->width('100%'),
                    Tables\Columns\TextColumn::make('code')
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Medium),
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold)
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Large),
                    Tables\Columns\TextColumn::make('service_type')
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

                    Tables\Columns\TextColumn::make('price')
                        ->money('MXN')
                        ->weight(FontWeight::Bold)
                        ->color('success')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::Large),
                ])->space(3),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('solicitar')
                    ->label('SOLICITAR')
                    ->button()
                    ->color('primary')
                    ->form(function (Service $record) {
                        if ($record->form_schema) {
                            return collect($record->form_schema)->map(function ($field) {
                                return Forms\Components\TextInput::make($field['name'])
                                    ->label($field['label'])
                                    ->required($field['required'] ?? false);
                            })->toArray();
                        }
                        
                        return [
                            Forms\Components\TextInput::make('input_data')
                                ->label('Información Requerida')
                                ->required(),
                        ];
                    })
                    ->action(function (Service $record, array $data) {
                        $user = User::find(auth()->id());
                        
                        if ($user->balance < $record->price) {
                            Notification::make()
                                ->title('Saldo Insuficiente')
                                ->body("Necesitas \${$record->price} pero tienes \${$user->balance}.")
                                ->danger()
                                ->send();
                            return;
                        }

                        $user->decrement('balance', $record->price);
                        
                        $user->orders()->create([
                            'service_id' => $record->id,
                            'input_data' => $data,
                            'status' => 'pending',
                        ]);

                        Notification::make()
                            ->title('Pedido Creado')
                            ->success()
                            ->send();
                    })
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
        return [
            //
        ];
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
