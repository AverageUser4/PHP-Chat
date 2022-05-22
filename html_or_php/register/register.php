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
  
  <h1><a href="../../index.php">Super Chat</a> - rejestracja</h1>

  <div id="formContainer">

    <form novalidate id="registerForm" method="post">

      <label for="email">Adres e-mail:</label>
      <input id="email" type="email" name="email">

      <label for="login">Login:</label>
      <input id="login" type="text" name="username">

      <label for="pass">Hasło:</label>
      <input id="pass" type="password" name="password">

      <label for="pass2">Powtórz hasło:</label>
      <input id="pass2" type="password" name="password2">

      <label for="gender">Wybierz płeć:</p>
      <select id="gender" name="gender">
        <option value="male">Mężczyzna</option>
        <option value="female">Kobieta</option>
        <option selected value="other">Inna</option>
      </select>
      
      <input type="submit" value="Zarejestruj się">
      
      <p id="registerPTag">Rejestrując się akceptujesz <a href="regulamin.txt">regulamin.</a></p>
      
    </form>

  </div>

  <script defer src="../../js/global/s1_global.js"></script>
  <script defer src="../../js/global/s3_validate.js"></script>
  
  <script defer src="../../js/accounts/s1_register_validate.js"></script>

</body>

</html>