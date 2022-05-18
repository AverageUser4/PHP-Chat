<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';


if(!isset($_SERVER['REMOTE_ADDR']) 
  || !filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
  ) $ip = 'unknown';
else $ip = $_SERVER['REMOTE_ADDR'];

$token = $_COOKIE['guest_token'] ?? null;


// if there's valid token return it to the user
require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure_return($PDO);

if(isset($token) && valid_guest_token($token)) {
  $PDO_Statement = $PDO -> prepare("SELECT * FROM guests WHERE token = :token");
  $PDO_Statement -> bindParam(':token', $token, PDO::PARAM_STR);
  $PDO_Statement -> execute();
  $result = $PDO_Statement -> fetch(PDO::FETCH_NUM);

  if($result)
    return $result[0] . '%' . $result[1];
}

// if there's no token or it's invalid, create new one and return it
$new_token = bin2hex(random_bytes(32));

$PDO_Statement = $PDO -> prepare('INSERT INTO guests VALUES (null, :new_token, :ip)');
$PDO_Statement -> bindParam(':new_token', $new_token, PDO::PARAM_STR);
$PDO_Statement -> bindParam(':ip', $ip, PDO::PARAM_STR);
if(!$PDO_Statement -> execute())
  failure_return('Nie udało się dodać do bazy.');

$PDO_Statement = $PDO -> query("SELECT * FROM guests WHERE token = '$new_token'", PDO::FETCH_NUM);
$result = $PDO_Statement -> fetch(PDO::FETCH_NUM);

if($result)
  return $result[0] . '%' . $result[1];

failure_return('Poważny błąd z bazą danych!!!!!');






