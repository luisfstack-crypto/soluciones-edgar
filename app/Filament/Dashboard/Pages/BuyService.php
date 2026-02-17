<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Service;
use App\Models\Order; // Importante añadir el Modelo Order
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
        
        if (!$serviceId) {
             Notification::make()->title('Servicio no especificado')->danger()->send();
             return redirect()->to('/app/services');
        }
        
        $this->service = Service::find($serviceId);

        if (! $this->service) {
             Notification::make()->title('Servicio no encontrado')->danger()->send();
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
        
        if ($this->service && !empty($this->service->form_schema)) {
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
        
        if (! $user->is_admin && $user->balance < $this->service->price) {
             Notification::make()
                ->title('Saldo Insuficiente')
                ->body("No tienes saldo suficiente. Costo: \${$this->service->price}. Saldo disponible: \${$user->balance}")
                ->danger()
                ->send();
             return;
        }

        try {
            DB::transaction(function () use ($user, $data) {
                $order = Order::create([
                     'user_id' => $user->id,
                     'service_id' => $this->service->id,
                     'input_data' => $data['input_data'] ?? [],
                     'status' => 'pending',
                     'price_at_purchase' => $this->service->price,
                ]);
                
                $admins = \App\Models\User::where('is_admin', true)->get();
                foreach ($admins as $admin) {
                    Notification::make()
                        ->title('Nuevo Pedido Registrado')
                        ->body("El usuario {$user->name} ha solicitado el servicio {$this->service->name}.")
                        ->info()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->label('Ver Pedido')
                                ->url("/admin/orders/{$order->id}/edit")
                        ])
                        ->sendToDatabase($admin);
                }
            });

            Notification::make()
                ->title('¡Servicio Contratado!')
                ->body('Tu pedido ha sido creado exitosamente. Un administrador lo revisará pronto.')
                ->success()
                ->send()
                ->sendToDatabase($user);

            if ($user->is_admin) {
                return redirect('/admin/services');
            }
            return redirect('/app/services'); 
            
        } catch (\Exception $e) {
             Notification::make()
                ->title('Error')
                ->body('Ocurrió un error al procesar la solicitud: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}