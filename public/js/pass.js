function checkPasswordMatch() {
      var password = $("#password").val();
      var confirmPassword = $("#password2").val();
      if (password != confirmPassword)
        $("#passmess").html("Пароли не совпадают!");
      else
        $("#passmess").html("OK !");
    }