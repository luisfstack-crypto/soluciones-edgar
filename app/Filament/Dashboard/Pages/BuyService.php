<?php

namespace App\Filament\Dashboard\Pages;

use App\Models\Order; // Importante añadir el Modelo Order
use App\Models\Service;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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
                    [
                        'name' => 'curp',
                        'label' => 'CURP',
                        'required' => true,
                        'regex' => '/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/',
                    ],
                ];
            }

            $schema = collect($formSchema)->map(function ($field) {
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

                if ($this->isPostalCodeField($field)) {
                    $locationFields = collect($this->service->form_schema ?? [])
                        ->filter(fn (array $candidate): bool => $this->isLocationField($candidate))
                        ->mapWithKeys(fn (array $candidate): array => [
                            $candidate['name'] => $this->locationFieldType($candidate),
                        ])
                        ->all();

                    $input
                        ->live(debounce: 400)
                        ->afterStateUpdated(function (Set $set, ?string $state) use ($locationFields): void {
                            $postalCode = trim((string) $state);

                            if (preg_match('/^\d{5}$/', $postalCode) !== 1) {
                                return;
                            }

                            try {
                                $response = Http::timeout(5)
                                    ->get("https://api.zippopotam.us/mx/{$postalCode}");

                                if (! $response->successful()) {
                                    return;
                                }

                                $place = data_get($response->json(), 'places.0');
                                $stateName = data_get($place, 'state');
                                $placeName = data_get($place, 'place name');

                                foreach ($locationFields as $fieldName => $fieldType) {
                                    $value = $fieldType === 'state' ? $stateName : $placeName;

                                    if (is_string($value) && $value !== '') {
                                        $set("input_data.{$fieldName}", $value);
                                    }
                                }
                            } catch (\Throwable) {
                                // Postal-code lookup is optional and must not interrupt order entry.
                            }
                        });
                }

                if (($this->service->code ?? '') === 'localizar-nss' && ($field['name'] ?? '') === 'curp') {
                    $input->helperText('El servicio devuelve el NSS al procesar la solicitud; solo se envía la CURP.');
                }

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

    private function isPostalCodeField(array $field): bool
    {
        foreach ([$field['name'] ?? '', $field['label'] ?? ''] as $value) {
            $identifier = Str::of((string) $value)
                ->ascii()
                ->lower()
                ->replaceMatches('/[^a-z0-9]+/', '_')
                ->trim('_')
                ->toString();

            if (
                str_contains($identifier, 'cp')
                || str_contains($identifier, 'codigo_postal')
                || str_contains($identifier, 'codigopostal')
                || str_contains($identifier, 'postal_code')
                || str_contains($identifier, 'postalcode')
            ) {
                return true;
            }
        }

        return false;
    }

    private function isLocationField(array $field): bool
    {
        return $this->locationFieldType($field) !== null;
    }

    private function locationFieldType(array $field): ?string
    {
        foreach ([$field['name'] ?? '', $field['label'] ?? ''] as $value) {
            $identifier = Str::of((string) $value)
                ->ascii()
                ->lower()
                ->replaceMatches('/[^a-z0-9]+/', '_')
                ->trim('_')
                ->toString();

            if (str_contains($identifier, 'estado') || str_contains($identifier, 'state')) {
                return 'state';
            }

            if (str_contains($identifier, 'municipio') || str_contains($identifier, 'alcaldia')) {
                return 'place';
            }
        }

        return null;
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
        if (($this->service->code ?? '') === 'localizar-nss') {
            $inputData = [
                'curp' => strtoupper(trim((string) ($inputData['curp'] ?? ''))),
            ];
            $data['input_data'] = $inputData;
        }

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