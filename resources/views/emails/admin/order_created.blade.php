<x-mail::message>
# Nueva Solicitud de Servicio

El usuario **{{ $order->user->name }}** ha solicitado un nuevo trámite.

**Detalles de la solicitud:**
- **Servicio:** {{ $order->service->name }}
- **Precio Pagado:** ${{ number_format($order->price_at_purchase, 2) }}

Por favor, revisa el pedido para procesarlo.

<x-mail::button :url="url('/admin/orders/' . $order->id . '/edit')">
Ver Pedido
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
