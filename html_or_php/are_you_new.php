<?php
// session_start();
// if(isset($_SESSION['id'])) {
//   header('Location: chat_room.php');
//   exit();
// }
// else if(isset($_COOKIE['access_token'])) {
//   header('Location: ../index.php');
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
  <title>Super Chat - czy masz już konto?</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/register_and_login.css">
  <style>
    #registerForm > h2 {
      font-size: 1.8rem;
      margin-bottom: 30px;
    }
    #registerForm > a {
      display: block;
      text-align: center;
      font-size: 1.4rem;
      line-height: 150%;
    }
  </style>

</head>

<body>
  
  <h1><a href="../index.php">Super Chat</a> - czy masz już konto?</h1>

  <div id="formContainer">

    <div novalidate id="registerForm">
      <h2>Masz już konto?</h2>
      <a href="login.php">Zaloguj się</a>
      <a href="register.php">Zarejestruj się</a>
      <a href="../php/accounts/create_guest.php">Kontynuuj jako gość</a>  
    </div>

  </div>

</body>

</html>