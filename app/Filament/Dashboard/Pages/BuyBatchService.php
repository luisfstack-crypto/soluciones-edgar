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

    protected static ?string $title = 'Constancias de Situación Fiscal (Lote)';

    protected static ?string $slug = 'solicitar-por-lote';

    protected static ?string $navigationGroup = 'Operaciones';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.dashboard.pages.buy-batch-service';

    public Service $service;

    public ?array $data = [];

    public function mount(): void
    {
        $this->service = Service::query()
            ->where('code', 'csf-curp-clon')
            ->firstOrFail();

        $this->form->fill([
            'requests' => [[]],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Solicitudes')
                    ->description('Agrega una solicitud por cada constancia que necesitas.')
                    ->schema([
                        Repeater::make('requests')
                            ->label('Solicitudes')
                            ->schema([
                                TextInput::make('curp')
                                    ->label('CURP o RFC')
                                    ->required()
                                    ->regex('/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z]{3}[A-Z0-9]{2}$/')
                                    ->validationMessages([
                                        'regex' => 'Ingresa una CURP válida de 18 caracteres en mayúsculas.',
                                    ]),
                                TextInput::make('lugarEmision')
                                    ->label('Lugar de Emisión')
                                    ->required(),
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
        $rows = $this->form->getState()['requests'] ?? [];
        $totalCost = round((float) $this->service->price * count($rows), 2);
        $authenticatedUser = auth()->user();

        DB::transaction(function () use ($authenticatedUser, $rows, $totalCost): void {
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