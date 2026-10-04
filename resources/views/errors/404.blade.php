@extends('errors.layout')

@section('title', 'Página no encontrada')
@section('code', '404')
@section('heading', 'No encontramos lo que buscas')
@section('message', 'Es posible que la dirección haya cambiado o ya no esté disponible.')
@section('extra')
    @if(request()->routeIs('orders.download'))
        <p class="message document-help">El enlace del documento pudo haber caducado. Pide uno nuevo por WhatsApp.</p>
    @endif
@endsection
