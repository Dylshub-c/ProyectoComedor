@extends('template')
@section('content')
<div class="container">
    <h2>Subir archivo ZIP con fotos</h2>
    <form action="{{ route('fotos.importar') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="zip" class="form-label">Archivo ZIP:</label>
            <input type="file" name="zip" class="form-control" required accept=".zip">
        </div>
        <button type="submit" class="btn btn-primary">Subir y Descomprimir</button>
    </form>
</div>
@endsection
