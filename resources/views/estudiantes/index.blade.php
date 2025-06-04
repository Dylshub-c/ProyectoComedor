@extends('template')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Lista de Estudiantes</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Nombre Completo</th>
                <th class="border p-2">Cédula</th>
                <th class="border p-2">Especialidad</th>
                <th class="border p-2">Sección</th>
                <th class="border p-2">Tipo de Beca</th>
                <th class="border p-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estudiantes as $estudiante)
                <tr class="border-t">
                    <td class="border p-2">
                        {{ $estudiante->persona->Nombre }}
                        {{ $estudiante->persona->PrimerApellido }}
                        {{ $estudiante->persona->SegundoApellido }}
                    </td>
                    <td class="border p-2">{{ $estudiante->persona->Cedula }}</td>
                    <td class="border p-2">{{ $estudiante->especialidade->propiedade->nombre ?? 'N/A' }}</td>
                    <td class="border p-2">{{ $estudiante->seccione->propiedade->nombre ?? 'N/A' }}</td>
                    <td class="border p-2">{{ $estudiante->tipoBeca->propiedade->nombre ?? 'N/A' }}</td>
                    <td class="border p-2 text-center">
                        <button
                            class="bg-blue-500 text-white px-3 py-1 rounded mostrar-foto-btn"
                            data-foto="{{ asset($estudiante->foto) }}">
                            Mostrar Foto
                        </button>
                    </td>
                </tr>
                <tr class="border-b">
                    <td colspan="6" class="p-2 hidden foto-container">
                        <img src="" alt="Foto estudiante" class="max-h-40 mx-auto rounded shadow-lg">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.querySelectorAll('.mostrar-foto-btn').forEach(button => {
        button.addEventListener('click', () => {
            const tr = button.closest('tr');
            const fotoRow = tr.nextElementSibling;
            const img = fotoRow.querySelector('img');

            if(fotoRow.classList.contains('hidden')){
                // Set image src from data attribute
                img.src = button.dataset.foto;
                fotoRow.classList.remove('hidden');
            } else {
                fotoRow.classList.add('hidden');
                img.src = '';
            }
        });
    });
</script>

@endsection
