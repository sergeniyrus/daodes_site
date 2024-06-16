
// копирование сидфразы
function copytext(el) {
      var $tmp = $("<textarea>");
      $("body").append($tmp);
      $tmp.val($(el).text()).select();
      document.execCommand("copy");
      $tmp.remove();
      alert("Сид-фраза скопирована в буфер обмена");
    }