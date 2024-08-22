import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


document.querySelector('.menu-button').addEventListener('click', function(e) {
  e.preventDefault(); // Предотвращаем переход по ссылке
  this.parentElement.classList.toggle('active'); // Переключаем класс active
});