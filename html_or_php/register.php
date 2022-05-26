<?php
// session_start();
// if(isset($_SESSION['id'])) {
//   header('Location: chat_room.php');
//   exit();
// }
// session_commit();
?>

<!DOCTYPE html>

<html lang="pl">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Chat - rejestracja</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/register_and_login.css">

</head>

<body>
  
  <h1><a href="../index.php">Super Chat</a> - rejestracja</h1>

  <div id="formContainer">

    <form novalidate id="registerForm" method="post">

      <label id="emailLabel" for="email">Adres e-mail: <span></span></label>
      <input value="adam@gmail.com" id="email" type="email" name="email">

      <label id="loginLabel" for="login">Login: <span></span></label>
      <input value="adam" id="login" type="text" name="username">

      <label id="passLabel" for="pass">Hasło: <span></span></label>
      <input value="qwerty" id="pass" type="password" name="password">

      <label for="pass2">Powtórz hasło:</label>
      <input value="qwerty" id="pass2" type="password" name="password2">

      <label for="gender">Wybierz płeć:</label>
      <select id="gender" name="gender">
        <option value="male">Mężczyzna</option>
        <option value="female">Kobieta</option>
        <option selected value="other">Inna</option>
      </select>
      
      <input type="submit" value="Zarejestruj się">
      
      <p>Rejestrując się akceptujesz <a href="regulamin.txt">regulamin.</a></p>
      <p>
        <a href="login.php">Zaloguj się</a> lub
        <a href="../php/accounts/create_guest.php">kontynuuj jako gość.</a>
      </p>
      
    </form>

  </div>

  <script defer src="../js/global/s1_global.js"></script>
  <script defer src="../js/global/s3_validate.js"></script>
  
  <script defer src="../js/accounts/s1_register_only.js"></script>
  <script defer src="../js/accounts/s2_register_and_login.js"></script>

</body>

</html>