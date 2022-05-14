<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

function namespace_1_failure($e) {
  return 'error%' . $e;
}

/* 
- jeżeli przesłano token, sprawdź czy jest w bazie danych
  * jeżeli jest nazwa użytownika w js zostaje ustawiona
  * jeżeli nie ma, użytkownik dostaje nową nazwę i token
*/


require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  namespace_1_failure($PDO);


/*
  jest ustawiony:
    - sprawdź bazę
    - jak nie ma to dodaj
    - jak jest zwróć true%nick
  nie jest:
    - dodaj do bazy
*/

$token = $_COOKIE['guest_token'] ?? null;

if(isset($token)
    && mb_strlen($token) == 64
    && ctype_alnum($token)
  ) {
  $PDO_Statement = $PDO -> prepare("SELECT * FROM guests WHERE token = :token");
  $PDO_Statement -> bindParam(':token', $token, PDO::PARAM_STR);
  $PDO_Statement -> execute();
  $result = $PDO_Statement -> fetch(PDO::FETCH_NUM);

  if($result)
    return $result[0] . '%' . $result[1];
}

$new_token = bin2hex(random_bytes(32));

$PDO_Statement = $PDO -> prepare('INSERT INTO guests VALUES (null, :new_token)');
$PDO_Statement -> bindParam(':new_token', $new_token, PDO::PARAM_STR);
if(!$PDO_Statement -> execute())
  namespace_1_failure('Nie udało się dodać do bazy.');

$PDO_Statement = $PDO -> query("SELECT * FROM guests WHERE token = '$new_token'", PDO::FETCH_NUM);
$result = $PDO_Statement -> fetch(PDO::FETCH_NUM);

if($result)
  return $result[0] . '%' . $result[1];

namespace_1_failure('Poważny błąd z bazą danych!!!!!');






