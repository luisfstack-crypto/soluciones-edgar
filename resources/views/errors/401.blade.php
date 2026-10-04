@extends('errors.layout')

@section('title', 'Inicia sesión')
@section('code', '401')
@section('heading', 'Necesitas iniciar sesión para ver esto')
@section('message', 'Inicia sesión en tu cuenta para continuar de forma segura.')
@section('button-url', url('/app/login'))
@section('button-label', 'Iniciar sesión')
