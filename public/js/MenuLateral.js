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
