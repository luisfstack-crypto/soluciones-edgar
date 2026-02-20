<?php

namespace App\Filament\Dashboard\Resources\ServiceResource\Pages;

use App\Filament\Dashboard\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create_service')
                ->label('Crear Nuevo Servicio')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->url('/admin/services/create')
                ->visible(fn () => auth()->user()->is_admin),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['Todos' => \Filament\Resources\Components\Tab::make('Todos')];
        
        $categories = \App\Models\Category::all();
        foreach ($categories as $category) {
            $tabs[$category->name] = \Filament\Resources\Components\Tab::make($category->name)
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('category_id', $category->id));
        }
        
        return $tabs;
    }
}
