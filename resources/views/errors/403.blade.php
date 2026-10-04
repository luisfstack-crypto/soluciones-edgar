@extends('errors.layout')

@section('title', 'Acceso denegado')
@section('code', '403')
@section('heading', 'No tienes permiso para ver esto')
@section('message', 'Este enlace no está disponible o ya no tienes acceso a este contenido.')
@section('extra')
    @if(request()->routeIs('orders.download'))
        <p class="message document-help">El enlace del documento pudo haber caducado o sido alterado. Pide un enlace nuevo por WhatsApp.</p>
    @endif
@endsection
