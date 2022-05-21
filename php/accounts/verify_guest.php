<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';


if(!isset($_SERVER['REMOTE_ADDR']) 
  || !filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
  ) $ip = 'unknown';
else $ip = $_SERVER['REMOTE_ADDR'];


// if there's valid token return it to the user
require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
failure_return($PDO);

if(valid_guest_token()) {
  $PDO_stm = $PDO -> prepare("SELECT * FROM guests WHERE token = :token");
  $PDO_stm -> bindParam(':token', $_COOKIE['guest_token'], PDO::PARAM_STR);
  $PDO_stm -> execute();
  $result = $PDO_stm -> fetch(PDO::FETCH_NUM);

  if($result)
    return $result[0] . '%' . $result[1];
}

// if there's no token or it's invalid, create new one and return it
$new_token = bin2hex(random_bytes(32));

$r = random_int(0, 255);
$g = random_int(0, 255);
$b = random_int(0, 255);
$a = random_int(3, 6) / 10;

$PDO_stm = $PDO -> prepare("INSERT INTO guests VALUES (null, :new_token, :ip, 'male', '$r,$g,$b,$a')");
$PDO_stm -> bindParam(':new_token', $new_token, PDO::PARAM_STR);
$PDO_stm -> bindParam(':ip', $ip, PDO::PARAM_STR);
if(!$PDO_stm -> execute())
  failure_return('Nie udało się dodać do bazy.');

$PDO_stm = $PDO -> query("SELECT * FROM guests WHERE token = '$new_token'", PDO::FETCH_NUM);
$result = $PDO_stm -> fetch(PDO::FETCH_NUM);

if($result)
  return $result[0] . '%' . $result[1];

failure_return('Poważny błąd z bazą danych!!!!!');






