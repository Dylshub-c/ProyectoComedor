
document.addEventListener("DOMContentLoaded", function () {
    $('.datepicker').datepicker({
    format: "dd/mm/yyyy",
    language: "es",
    todayHighlight: true,
    autoclose: true
    });

    document.getElementById('btnBuscar').addEventListener('click', function () {
    const searchBox = document.getElementById('searchInput');
    searchBox.style.display = 'block';
    searchBox.focus();
    });

    document.getElementById('searchInput').addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tablaEstudiantes tbody tr');
    rows.forEach(row => {
        const nameCell = row.cells[1].textContent.toLowerCase();
        row.style.display = nameCell.includes(filter) ? '' : 'none';
    });
    });
});
  
/*---------------------Cerrar navbar al tocar afuera-----------------------*/

document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll("#navbarNav .nav-link");
    const navbarCollapse = document.getElementById("navbarNav");

    navLinks.forEach(function (link) {
        link.addEventListener("click", function () {
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        });
    });
});

/*-------------------------------------------------------------------------*/