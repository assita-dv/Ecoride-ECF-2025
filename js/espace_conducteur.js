document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("btnTrajets");
    const arrow = document.getElementById("arrow");
    const bloc = document.getElementById("trajetsContainer");

    btn.addEventListener("click", () => {
        bloc.classList.toggle("visible");
        arrow.classList.toggle("fa-chevron-down");
        arrow.classList.toggle("fa-chevron-up");
    });
});

function toggleSection(id) {
    const section = document.getElementById(id);
    section.classList.toggle('d-none');
}