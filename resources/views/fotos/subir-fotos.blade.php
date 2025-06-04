@extends('template')

@section('content')
<div class="container mx-auto p-4 max-w-md">
    <h1 class="text-xl font-bold mb-4">Subir archivo ZIP con fotos</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('subir-fotos.importar') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label for="zip" class="block mb-2 font-medium">Archivo ZIP</label>
            <input type="file" name="zip" id="zip" accept=".zip" required class="border rounded p-2 w-full" />
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Subir y Descomprimir</button>
    </form>
</div>
@endsection
