@extends('template')

@section('title', 'Listado de Especialidades')

@section('content')
<div class="container">
    <h1>Especialidades</h1>
    <a href="{{ route('especialidades.importar.form') }}" class="btn btn-primary mb-3">Importar Especialidades</a>

    {{-- Aquí puedes mostrar la lista de especialidades --}}
    @if($especialidades->count())
        <ul>
            @foreach($especialidades as $especialidad)
                <li>{{ $especialidad->propiedade->nombre }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay especialidades registradas.</p>
    @endif
</div>
@endsection
