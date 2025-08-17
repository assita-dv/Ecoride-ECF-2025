

  // JS toggle menu
const toggle = document.querySelector('.navbar-toggler');
const menu = document.querySelector('.navbar-collapse');

toggle.addEventListener('click', () => {
  menu.classList.toggle('show'); // toggle la classe show
});
