@extends('template')

@section('title', 'Importar Especialidades')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Importar Especialidades desde Excel</h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('especialidades.importar') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="archivo" class="form-label">Archivo Excel</label>
                    <input type="file" class="form-control @error('archivo') is-invalid @enderror" id="archivo" name="archivo" accept=".xlsx,.xls" required>
                    @error('archivo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="bi bi-upload"></i> Importar Especialidades
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
