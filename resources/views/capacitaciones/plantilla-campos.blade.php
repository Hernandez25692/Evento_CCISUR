@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/fonts-visby.css') }}">

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h5 mb-0">
                    <i class="fas fa-arrows-alt"></i>
                    Posicionar campos del diploma — {{ $capacitacion->nombre }}
                </h3>
            </div>
            <div class="card-body">
                <x-diploma-campos-editor :image-url="asset('storage/' . $plantilla->fondo)"
                    :fondo-width="$plantilla->fondo_width" :fondo-height="$plantilla->fondo_height" :campos="$campos"
                    :etiquetas="$etiquetas" :fuentes="$fuentes" :defaults="$defaults" :contenidos="$contenidos"
                    :firmas="$firmas" :participantes="$participantes" :participante-inicial="$participanteInicial"
                    :save-url="route('capacitaciones.plantilla.campos.store', $capacitacion->id)"
                    :back-url="route('capacitaciones.plantilla', $capacitacion->id)" />
            </div>
        </div>
    </div>
@endsection
