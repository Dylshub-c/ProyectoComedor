document.getElementById('confirmarAsistencia').addEventListener('click', function() {
    // Obtener valores
    const fecha = document.getElementById('fecha').value;
    const tipoAsistencia = document.getElementById('tipoAsistencia').value;
    const estado = document.getElementById('estadoAsistencia').value;
    const observaciones = document.getElementById('explicacion').value;

    // Validación básica
    if (!fecha || !tipoAsistencia || !estado) {
        alert('Por favor complete todos los campos requeridos.');
        return;
    }

    // Token CSRF
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/ruta/guardar-asistencia-rapida', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            fecha_hora: fecha,
            tipo_asistencia: tipoAsistencia,
            estado: estado,
            observaciones: observaciones
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.message){
            // Mostrar modal de éxito
            var modalExito = new bootstrap.Modal(document.getElementById('modalExito'));
            modalExito.show();
        } else if(data.error){
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));
});
