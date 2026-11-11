<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Order; // Importante añadir el Modelo Order
use App\Models\Service;
use App\Services\DocMxService;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        if (! $this->service->isAvailable()) {
            Notification::make()
            ->title('Servicio no disponible')
            ->body($this->service->unavailableReason())
                ->warning()
                ->send();

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
        
        if ($this->service && (! empty($this->service->form_schema) || ($this->service->code ?? '') === 'localizar-nss')) {
            $formSchema = $this->service->form_schema;

            if (($this->service->code ?? '') === 'localizar-nss') {
                $formSchema = [
                    ['name' => 'curp', 'label' => 'CURP', 'required' => true],
                    ['name' => 'nss', 'label' => 'NSS (11 dígitos)', 'required' => false],
                ];
            }

            $schema = collect($formSchema)->map(function ($field) {
                if (($this->service->code ?? '') === 'localizar-nss' && ($field['name'] ?? '') === 'curp') {
                    return TextInput::make('input_data.curp')
                        ->label('CURP')
                        ->required()
                        ->regex('/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/')
                        ->live(debounce: 500)
                        ->afterStateUpdated(function (Set $set, ?string $state, DocMxService $docMx): void {
                            $set('input_data.nss', null);
                            $curp = strtoupper(trim((string) $state));

                            if (preg_match('/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/', $curp) !== 1) {
                                return;
                            }

                            try {
                                $nss = $docMx->lookupNssByCurp($curp);
                            } catch (\LogicException $exception) {
                                Notification::make()
                                    ->title('Autollenado de NSS no disponible')
                                    ->body($exception->getMessage())
                                    ->warning()
                                    ->send();

                                return;
                            }

                            if ($nss === null) {
                                return;
                            }

                            if (preg_match('/^\d{11}$/', $nss) !== 1) {
                                Notification::make()
                                    ->title('Respuesta de NSS inválida')
                                    ->body('La consulta debe devolver un NSS de exactamente 11 dígitos.')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $set('input_data.nss', $nss);
                        });
                }

                if (($this->service->code ?? '') === 'localizar-nss' && ($field['name'] ?? '') === 'nss') {
                    return TextInput::make('input_data.nss')
                        ->label('NSS (11 dígitos)')
                        ->readOnly()
                        ->dehydrated(false)
                        ->helperText('El NSS se mostrará aquí cuando la consulta CURP → NSS esté configurada.');
                }

                if (($this->service->code ?? '') === 'recibo-cfe' && ($field['name'] ?? '') === 'cfe_number') {
                    return TextInput::make('input_data.cfe_number')
                        ->label('Número de servicio (12 dígitos)')
                        ->required()
                        ->inputMode('numeric')
                        ->regex('/^\d{12}$/')
                        ->placeholder('123456789012')
                        ->validationMessages([
                            'regex' => 'El número de servicio debe tener exactamente 12 dígitos.',
                        ]);
                }

                if (($this->service->code ?? '') === 'recibo-cfe' && ($field['name'] ?? '') === 'nombre_titular') {
                    return TextInput::make('input_data.nombre_titular')
                        ->label($field['label'] ?? 'Nombre del titular')
                        ->required()
                        ->maxLength(150)
                        ->placeholder('Ej. PEREZ LOPEZ JUAN')
                        ->helperText($field['helperText'] ?? $field['helper_text'] ?? null)
                        ->dehydrateStateUsing(fn (?string $state): ?string => $state === null ? null : mb_strtoupper(trim($state)));
                }

                if (($this->service->code ?? '') === 'recibo-cfe' && ($field['name'] ?? '') === 'total_pagar') {
                    return TextInput::make('input_data.total_pagar')
                        ->label($field['label'] ?? 'Total a pagar')
                        ->required()
                        ->regex('/^\d+$/')
                        ->gt(0)
                        ->placeholder('1250')
                        ->helperText($field['helperText'] ?? $field['helper_text'] ?? null)
                        ->validationMessages([
                            'regex' => 'El total a pagar debe ser un entero positivo, sin centavos.',
                            'gt' => 'El total a pagar debe ser mayor que cero.',
                        ]);
                }

                if (($field['name'] ?? '') === 'lugarEmision') {
                    return TextInput::make('input_data.lugarEmision')
                        ->label($field['label'] ?? 'Lugar de Emisión')
                        ->placeholder('Ej. Cuauhtémoc, Ciudad de México')
                        ->required();
                }

                $input = TextInput::make("input_data.{$field['name']}")
                    ->label($field['label'])
                    ->required($field['required'] ?? false);

                if (!empty($field['numeric'])) {
                    $input->numeric();
                }

                if (!empty($field['rules'])) {
                    $input->rules($field['rules']);
                }

                if (!empty($field['validationMessages'])) {
                    $input->validationMessages($field['validationMessages']);
                }

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
        $this->service?->refresh();

        if (! $this->service?->isAvailable()) {
            Notification::make()
            ->title('Servicio no disponible')
            ->body($this->service?->unavailableReason() ?? 'Este servicio no está disponible por el momento')
                ->warning()
                ->send();

            return redirect()->to('/app/services');
        }

        $data = $this->form->getState();

        $hasLugarEmision = collect($this->service?->form_schema ?? [])
            ->contains(fn (array $field): bool => ($field['name'] ?? '') === 'lugarEmision');

        if ($hasLugarEmision && blank(data_get($data, 'input_data.lugarEmision'))) {
            throw ValidationException::withMessages([
                'data.input_data.lugarEmision' => 'Indica el lugar de emisión.',
            ]);
        }

        $user = auth()->user();
        
        if (! $user->is_admin && $user->balance < $this->service->price) {
             Notification::make()
                ->title('Saldo Insuficiente')
                ->body("No tienes saldo suficiente. Costo: \${$this->service->price}. Saldo disponible: \${$user->balance}")
                ->danger()
                ->send();
             return;
        }

        $inputData = $data['input_data'] ?? [];
        if (($this->service->code ?? '') === 'recibo-cfe' && isset($inputData['nombre_titular'])) {
            $inputData['nombre_titular'] = mb_strtoupper(trim((string) $inputData['nombre_titular']));
            $data['input_data'] = $inputData;
        }

        $existingOrder = false;
        try {
            $existingOrder = Order::where('user_id', $user->id)
                ->where('service_id', $this->service->id)
                ->whereIn('status', ['pending', 'processing'])
                ->whereJsonContains('input_data', $inputData)
                ->exists();
        } catch (\Throwable $e) {
            $existingOrder = Order::where('user_id', $user->id)
                ->where('service_id', $this->service->id)
                ->whereIn('status', ['pending', 'processing'])
                ->get()
                ->contains(function ($order) use ($inputData) {
                    return $order->input_data == $inputData;
                });
        }

        if ($existingOrder) {
            Notification::make()
                ->title('Ya cuenta con una solicitud en proceso con el mismo Folio')
                ->danger()
                ->send();
            return;
        }

        try {
            DB::transaction(function () use ($user, $data) {
                $service = Service::query()->lockForUpdate()->findOrFail($this->service->id);

                if (! $service->isAvailable()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'data' => $service->unavailableReason(),
                    ]);
                }

                $order = Order::create([
                     'user_id' => $user->id,
                     'service_id' => $service->id,
                     'input_data' => $data['input_data'] ?? [],
                     'status' => 'pending',
                     'price_at_purchase' => $service->price,
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
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Notification::make()
                ->title('Servicio no disponible')
                ->body(collect($e->errors())->flatten()->first() ?? 'Este servicio no está disponible por el momento')
                ->warning()
                ->send();
        } catch (\Exception $e) {
             Notification::make()
                ->title('Error')
                ->body('Ocurrió un error al procesar la solicitud: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}