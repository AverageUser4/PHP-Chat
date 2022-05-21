<!DOCTYPE html>

<html lang="pl">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Chat - Rejestracja</title>
  <link rel="stylesheet" href="../../css/global.css">
  <link rel="stylesheet" href="../../css/register.css">

</head>

<body>
  
  <div id="formContainer">

    <form id="registerForm" action="register_script.php" method="post">

      <label for="email">Adres e-mail:</label>
      <input id="email" type="email" name="email">

      <label for="login">Login:</label>
      <input id="login" type="text" name="username">

      <label for="pass">Hasło:</label>
      <input id="pass" type="password" name="password">

      <label for="pass2">Powtórz hasło:</label>
      <input id="pass2" type="password" name="password2">

      <input type="submit" value="Zarejestruj się">

      <p>Rejestrując się akceptujesz <a href="regulamin.txt">regulamin.</a></p>
      
    </form>

  </div>

  <script>

    const form = document.getElementById('registerForm');
    form.addEventListener('submit', validateForm);

    function validateForm(event) {

      
      event.preventDefault();
    }

  </script>

</body>

</html>