// копирование сидфразы

function copytext(el) {
  var $tmp = $("<textarea>");
  $("body").append($tmp);
  $tmp.val($(el).text().trim().replace(/\s+/g, ' ')).select();
  document.execCommand("copy");
  $tmp.remove();
  alert("Сид-фраза скопирована в буфер обмена");

  // Активируем кнопку "Я Сохранил сид-фразу" после копирования
  var saveButton = document.getElementById('saveButton');
  saveButton.removeAttribute('disabled');
  saveButton.classList.remove('btn-disabled');
}
    