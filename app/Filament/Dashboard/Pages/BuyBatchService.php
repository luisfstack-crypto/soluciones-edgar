<?php

namespace App\Filament\Dashboard\Pages;

use App\Filament\Dashboard\Resources\OrderResource;
use App\Jobs\ProcessBatchOrderJob;
use App\Models\Service;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BuyBatchService extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Solicitar por Lote';

    protected static ?string $title = 'Solicitar por lote';

    protected static ?string $slug = 'solicitar-por-lote';

    protected static ?string $navigationGroup = 'Operaciones';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.dashboard.pages.buy-batch-service';

    public Service $service;

    public ?array $data = [];

    public function mount()
    {
        $serviceCode = (string) request()->query('service', 'csf-curp-clon');

        $this->service = Service::query()
            ->whereIn('code', ['csf-curp-clon', 'localizar-nss'])
            ->where('code', $serviceCode)
            ->firstOrFail();

        if (! $this->service->isAvailable()) {
            Notification::make()
                ->title('Servicio no disponible')
                ->body($this->service->unavailableReason())
                ->warning()
                ->send();
        }

        $this->form->fill([
            'requests' => [[]],
        ]);
    }

    public function getTitle(): string
    {
        return ($this->service->code ?? '') === 'localizar-nss'
            ? 'Localizar NSS (Lote)'
            : 'Constancias de Situación Fiscal (Lote)';
    }

    public function form(Form $form): Form
    {
        $isNssLookup = ($this->service->code ?? '') === 'localizar-nss';

        return $form
            ->schema([
                Section::make('Solicitudes')
                    ->description($isNssLookup
                        ? 'Agrega una CURP por solicitud. El NSS lo devuelve el servicio al procesar cada orden.'
                        : 'Agrega una solicitud por cada constancia que necesitas.')
                    ->schema([
                        Repeater::make('requests')
                            ->label('Solicitudes')
                            ->schema($isNssLookup
                                ? [
                                    TextInput::make('curp')
                                        ->label('CURP')
                                        ->required()
                                        ->regex('/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/')
                                        ->validationMessages([
                                            'regex' => 'Ingresa una CURP válida de 18 caracteres en mayúsculas.',
                                        ]),
                                ]
                                : [
                                    TextInput::make('curp')
                                        ->label('CURP o RFC')
                                        ->required()
                                        ->regex('/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/')
                                        ->validationMessages([
                                            'regex' => 'Ingresa una CURP válida de 18 caracteres en mayúsculas.',
                                        ]),
                                    TextInput::make('lugarEmision')
                                        ->label('Lugar de Emisión')
                                        ->placeholder('Ej. Cuauhtémoc, Ciudad de México')
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'Indica el lugar de emisión.',
                                        ]),
                                ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel('Agregar solicitud')
                            ->reorderable(false)
                            ->live(),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $this->service->refresh();

        if (! $this->service->isAvailable()) {
            Notification::make()
                ->title('Servicio no disponible')
                ->body($this->service->unavailableReason())
                ->warning()
                ->send();

            return redirect()->to(OrderResource::getUrl('index', panel: 'dashboard'));
        }

        $rawState = $this->form->getRawState();
        $rawRows = $rawState['requests'] ?? [];
        $hasLugarEmision = collect($this->service->form_schema ?? [])
            ->contains(fn (array $field): bool => ($field['name'] ?? '') === 'lugarEmision');

        if ($hasLugarEmision) {
            foreach ($rawRows as $key => $row) {
                if (blank($row['lugarEmision'] ?? null)) {
                    throw ValidationException::withMessages([
                        "data.requests.{$key}.lugarEmision" => 'Indica el lugar de emisión.',
                    ]);
                }
            }
        }

        $state = $this->form->getState();
        $rows = $state['requests'] ?? [];

        if (($this->service->code ?? '') === 'localizar-nss') {
            $rows = collect($rows)
                ->map(fn (array $row): array => [
                    'curp' => strtoupper(trim((string) ($row['curp'] ?? ''))),
                ])
                ->all();
        }

        $totalCost = round((float) $this->service->price * count($rows), 2);
        $authenticatedUser = auth()->user();

        DB::transaction(function () use ($authenticatedUser, $rows, $totalCost): void {
            $service = Service::query()->lockForUpdate()->findOrFail($this->service->id);

            if (! $service->isAvailable()) {
                throw ValidationException::withMessages([
                    'data.requests' => $service->unavailableReason(),
                ]);
            }

            $user = User::query()
                ->lockForUpdate()
                ->findOrFail($authenticatedUser->id);

            if ($totalCost > (float) $user->balance) {
                throw ValidationException::withMessages([
                    'data.requests' => 'Fondos insuficientes para enviar este lote.',
                ]);
            }

            $user->balance = (float) $user->balance - $totalCost;
            $user->save();

            $jobs = collect($rows)
                ->map(fn (array $row): ProcessBatchOrderJob => new ProcessBatchOrderJob($user, $this->service, $row))
                ->all();

            Bus::batch($jobs)->allowFailures()->dispatch();
        });

        Notification::make()
            ->success()
            ->title('Lote enviado a proceso')
            ->send();

        return redirect()->to(OrderResource::getUrl('index', panel: 'dashboard'));
    }
}