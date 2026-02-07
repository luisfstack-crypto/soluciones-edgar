<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Asesoria extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Asesoría';
    protected static ?string $title = 'Asesoría en Línea';
    protected static ?string $slug = 'asesoria';

    protected static string $view = 'filament.pages.asesoria';

    public $client_name = '';

    public function mount()
    {
        $this->client_name = auth()->user()->name ?? '';
    }

    public function submit()
    {
        $this->validate([
            'client_name' => 'required|string|min:3|max:255',
        ]);

        \App\Models\Consultation::create([
            'name' => $this->client_name,
        ]);
        $this->dispatch('open-whatsapp', name: $this->client_name);
        
        $this->client_name = '';
    }
}
