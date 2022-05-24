<?php

/*
- access token dla gościa jest zawsze losowy, a dla użytkownika
wynosi null, chyba że zaznaczył nie wylogowuj mnie
- użytownik bez ciasteczka access_token powinien zostać zapytany
czy chece zrobić konto, czy kontynuować jako gość, żeby nie robić
bez potrzeby kont gościa
- jeżeli access token wynosi null, przenieś do strony logowania
- jeżeli nie, zaloguj użytkownika / gościa
*/

session_start();
set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

if(!isset($_COOKIE['access_token'])) {
  header('Location: ../html_or_php/register.php');
  exit();
}

require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO) {
  $_SESSION['sww_err'] = $PDO;
  header("Location: ../../html_or_php/something_went_wrong.php");
  exit();
}

if(valid_access_token()) {
  $PDO_stm = $PDO -> prepare("SELECT id, username, account_type, 
  gender, color FROM users WHERE access_token = :token");
  $PDO_stm -> bindParam(':token', $_COOKIE['access_token'], PDO::PARAM_STR);
  $PDO_stm -> execute();
  $result = $PDO_stm -> fetch(PDO::FETCH_ASSOC);

  if($result) {
    $_SESSION['id'] = $result['id'];
    $_SESSION['username'] = $result['username'];
    $_SESSION['account_type'] = $result['account_type'];
    $_SESSION['gender'] = $result['gender'];
    $_SESSION['color'] = $result['color'];
    header('Location: ../html_or_php/chat_room.php');
    exit();
  }
}

header('Location: ../../html_or_php/register.php');