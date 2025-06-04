@extends('template')

@section('content')
<div class="container mt-4">
    <h2>Importar Estudiantes</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('estudiantes.importar') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="archivo" class="form-label">Archivo Excel:</label>
            <input type="file" name="archivo" class="form-control" required accept=".xlsx,.xls">
        </div>

        <button type="submit" class="btn btn-primary">Importar</button>
    </form>
</div>
@endsection
