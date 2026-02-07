<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Service;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class BuyService extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.dashboard.pages.buy-service';
    
    protected static bool $shouldRegisterNavigation = false;

    public ?Service $service = null;
    public ?array $data = [];

    public function mount()
    {
        $serviceId = request()->query('service');
        // Fallback or validation
        if (!$serviceId) {
             return redirect()->to('/app/services');
        }
        
        $this->service = Service::find($serviceId);

        if (! $this->service) {
             return redirect()->to('/app/services');
        }

        $this->form->fill();
    }
    
    public function getTitle(): string 
    {
        return $this->service ? "Contratar: " . $this->service->name : 'Contratar Servicio';
    }

    public function form(Form $form): Form
    {
        $schema = [];
        
        if ($this->service && $this->service->form_schema) {
             $schema = collect($this->service->form_schema)->map(function ($field) {
                $input = TextInput::make("input_data.{$field['name']}")
                    ->label($field['label'])
                    ->required($field['required'] ?? false);

                if (isset($field['regex'])) {
                    $input->regex($field['regex']);
                }

                return $input;
            })->toArray();
        } else {
            $schema = [
                TextInput::make('input_data.text')
                    ->label('Detalles adicionales')
                    ->required(),
            ];
        }

        return $form
            ->schema([
                Section::make('Detalles del Pedido')
                    ->description('Por favor complete la información necesaria para su trámite.')
                    ->schema($schema)
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $data = $this->form->getState();
        $user = auth()->user();
        
        if ($user->balance < $this->service->price) {
             Notification::make()
                ->title('Saldo Insuficiente')
                ->body("No tienes saldo suficiente. Costo: \${$this->service->price}. Saldo: \${$user->balance}")
                ->danger()
                ->send();
             return;
        }

        try {
            DB::transaction(function () use ($user, $data) {
                // Create Order
                $order = \App\Models\Order::create([
                     'user_id' => $user->id,
                     'service_id' => $this->service->id,
                     'input_data' => $data['input_data'] ?? [],
                     'status' => 'pending',
                ]);
                
                // Deduct Balance
                // Using the method from User model if available, otherwise manual
                if (method_exists($user, 'subtractBalance')) {
                    $user->subtractBalance($this->service->price, "Servicio: {$this->service->name}", $order);
                } else {
                     $user->balance -= $this->service->price;
                     $user->save();
                     // Log transaction if needed
                }
            });

            Notification::make()
                ->title('¡Servicio Contratado!')
                ->body('Tu pedido ha sido creado exitosamente.')
                ->success()
                ->send();

            return redirect()->to('/app/services'); 
            
        } catch (\Exception $e) {
             Notification::make()
                ->title('Error')
                ->body('Ocurrió un error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
