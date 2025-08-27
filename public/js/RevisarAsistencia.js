document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  const popover = document.getElementById('popover');
  const modalConfirmacion = document.getElementById('modalConfirmacion');
  const btnAceptarModal = document.getElementById('btnAceptarModal');

  let selectedDateStr = null;
  let lastJsEvent = null;
  const fechasAceptadas = new Set();
  let interaccionUsuario = false;

  // Convertir asistenciasEstudiante a eventos para FullCalendar
  const eventosAsistencia = Object.entries(asistenciasEstudiante).map(([fecha, estado]) => {
    return {
      start: fecha,
      allDay: true,
      display: 'block',
      classNames: ['evento-icono'],
      extendedProps: {
        esMarca: true,
        icono: estado === 'presente' ? 'fa-solid fa-square-check' : 'fa-solid fa-square-xmark',
        color: estado === 'presente' ? 'green' : 'red',
      }
    };
  });

  const calendar = new FullCalendar.Calendar(calendarEl, {
    locale: 'es',
    initialView: 'dayGridMonth',
    selectable: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,listWeek'
    },
    events: eventosAsistencia,  // Asignamos eventos aquí

    select: function (info) {
      interaccionUsuario = true;
      selectedDateStr = info.startStr;
      lastJsEvent = info.jsEvent;

      const hoy = new Date();
      const yyyy = hoy.getFullYear();
      const mm = String(hoy.getMonth() + 1).padStart(2, '0');
      const dd = String(hoy.getDate()).padStart(2, '0');
      const hoyStr = `${yyyy}-${mm}-${dd}`;

      if (selectedDateStr === hoyStr || fechasAceptadas.has(selectedDateStr)) {
        mostrarPopover(lastJsEvent);
      } else {
        if (interaccionUsuario) {
          modalConfirmacion.style.display = 'flex';
        }
      }
    },

    eventContent: function (arg) {
      if (arg.event.extendedProps.esMarca) {
        const icon = document.createElement('i');
        icon.className = arg.event.extendedProps.icono;
        icon.style.color = arg.event.extendedProps.color;
        return { domNodes: [icon] };
      }
      return { html: arg.event.title };
    }
  });

  calendar.render();

  function mostrarPopover(jsEvent) {
    const left = jsEvent.pageX;
    const top = jsEvent.pageY;
    popover.style.left = left + 'px';
    popover.style.top = top + 'px';
    popover.style.display = 'block';
  }

  function hidePopover() {
    popover.style.display = 'none';
    selectedDateStr = null;
    lastJsEvent = null;
  }

  document.getElementById('btnAsistencia').addEventListener('click', function () {
    marcarDia('fa-solid fa-square-check', 'green');
    hidePopover();
  });

  document.getElementById('btnFalta').addEventListener('click', function () {
    marcarDia('fa-solid fa-square-xmark', 'red');
    hidePopover();
  });

  document.getElementById('btnEvento').addEventListener('click', function () {
    marcarDia('fa-solid fa-triangle-exclamation', 'blue');
    hidePopover();
  });

  function marcarDia(icono, color) {
    if (!selectedDateStr) return;

    const existente = calendar.getEvents().find(e =>
      e.startStr === selectedDateStr && e.extendedProps.esMarca
    );

    if (existente) {
      if (existente.extendedProps.icono === icono && existente.extendedProps.color === color) {
        existente.remove();
        return;
      } else {
        existente.remove();
      }
    }

    calendar.addEvent({
      start: selectedDateStr,
      allDay: true,
      display: 'block',
      classNames: ['evento-icono'],
      extendedProps: {
        esMarca: true,
        icono: icono,
        color: color
      }
    });
  }

  // Ocultar popover si se hace click afuera
  document.addEventListener('click', function (e) {
    if (!popover.contains(e.target) && !calendarEl.contains(e.target) && !modalConfirmacion.contains(e.target)) {
      hidePopover();
      modalConfirmacion.style.display = 'none';
    }
  });

  btnAceptarModal.addEventListener('click', function () {
    if (selectedDateStr) fechasAceptadas.add(selectedDateStr);
    modalConfirmacion.style.display = 'none';
    if (lastJsEvent) {
      mostrarPopover(lastJsEvent);
    }
  });

});
