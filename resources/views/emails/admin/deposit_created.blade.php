<x-mail::message>
# Nueva Solicitud de Abono de Saldo

El usuario **{{ $depositRequest->user->name }}** ha solicitado un abono de saldo.

**Detalles de la solicitud:**
- **Monto:** ${{ number_format($depositRequest->amount, 2) }}
- **Clave de Rastreo:** {{ $depositRequest->tracking_key }}
- **Banco:** {{ $depositRequest->bank_name }}

Por favor, revisa esta solicitud en el panel de administración.

<x-mail::button :url="url('/admin/deposit-requests/' . $depositRequest->id . '/edit')">
Ver Solicitud
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
