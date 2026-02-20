<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Catálogo de Servicios';
    protected static ?string $modelLabel = 'Servicio';
    protected static ?string $pluralModelLabel = 'Servicios';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Read only
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
                        ->height('200px')
                        ->width('100%')
                        ->extraImgAttributes(['class' => 'object-contain w-full h-full bg-gray-50 dark:bg-gray-900 rounded-t-xl'])
                        ->defaultImageUrl(url('/images/logo.png')),
                    
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight('bold')
                            ->size(Tables\Columns\TextColumn\TextColumnSize::Large)
                            ->color('primary')
                            ->searchable(),
                            
                        Tables\Columns\TextColumn::make('price')
                            ->money('MXN')
                            ->badge()
                            ->color('success'),
                            
                        Tables\Columns\TextColumn::make('processing_time')
                            ->icon('heroicon-m-clock')
                            ->color('gray')
                            ->size('sm'),
                            
                        Tables\Columns\TextColumn::make('description')
                            ->limit(80)
                            ->color('gray')
                            ->size('sm')
                            ->searchable(),
                    ])->space(2)->extraAttributes(['class' => 'p-5']),
                ])->space(0)->extraAttributes(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden transform transition hover:shadow-lg hover:-translate-y-1 duration-300']),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Filtrar por Etiqueta / Tipo')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Editar Servicio')
                    ->icon('heroicon-m-pencil-square')
                    ->color('gray')
                    ->url(fn (Service $record) => "/admin/services/{$record->id}/edit")
                    ->visible(fn () => auth()->user()->is_admin)
                    ->extraAttributes(['class' => 'w-full justify-center mb-2 mx-4']),

                Tables\Actions\Action::make('hire')
                    ->label(fn () => auth()->user()->is_admin ? 'Solicitar (Gratis)' : 'Contratar Servicio')
                    ->icon('heroicon-m-shopping-bag')
                    ->button()
                    ->size('lg')
                    ->color('primary')
                    ->url(fn (Service $record) => route('filament.dashboard.pages.buy-service') . '?service=' . $record->id)
                    ->extraAttributes(['class' => 'w-full justify-center mb-4 mx-4']),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
        ];
    }
}
