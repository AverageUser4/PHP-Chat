<?php
// session_start();
// if(isset($_SESSION['id'])) {
//   header('Location: chat_room.php');
//   exit();
// }
/// session_commit();
?>

<!DOCTYPE html>

<html lang="pl">

<head>

  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Super Chat - logowanie</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/register_and_login.css">

</head>

<body>
  
  <h1><a href="../index.php">Super Chat</a> - logowanie</h1>

  <div id="formContainer">

    <form novalidate id="registerForm" method="post">
      
      <label id="loginLabel" for="login">Login / e-mail: <span></span></label>
      <input value="adam" id="login" type="text" name="username">

      <label id="passLabel" for="pass">Hasło: <span></span></label>
      <input value="qwerty" id="pass" type="password" name="password">
      
      <label id="dl" for="dont_logout">Nie wylogowuj mnie
        <input checked id="dont_logout" type="checkbox" name="dont_logout" value="true">
      </label>
      
      <input type="submit" value="Zaloguj się">
      
      <p>
        <a href="register.php">Zarejestruj się</a> lub
        <a href="../php/accounts/create_guest.php">kontynuuj jako gość.</a>
      </p>

    </form>

  </div>

  <script defer src="../js/global/s1_global.js"></script>
  <script defer src="../js/global/s3_validate.js"></script>
  
  <script defer src="../js/accounts/s2_register_and_login.js"></script>

</body>

</html>