<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

function insert_new_user
(
  $ip = 'null',
  $email = 'null',
  $username = 'null',
  $password = 'null',
  $gender = 'other',
  $account_type = 'guest'
) {
  require_once 'global/pdo_connect.php';
  if(!$PDO instanceof PDO)
    return [false, $PDO];

  if($account_type !== 'guest') {
    $PDO_stm = $PDO -> prepare("SELECT id FROM users WHERE email = :email");
    $PDO_stm -> bindParam(':email', $email);
    $PDO_stm -> execute();
    $result = $PDO_stm -> fetch(PDO::FETCH_NUM);
    if($result)
      return [false, 'eE-mail zajęty.'];
  
    $PDO_stm = $PDO -> prepare("SELECT id FROM users WHERE username = :username");
    $PDO_stm -> bindParam(':username', $username);
    $PDO_stm -> execute();
    $result = $PDO_stm -> fetch(PDO::FETCH_NUM);
    if($result)
      return [false, 'uLogin zajęty.'];
  }

  $r = random_int(0, 255);
  $g = random_int(0, 255);
  $b = random_int(0, 255);
  $a = random_int(3, 6) / 10;
  
  if($account_type === 'guest') {
    $access_token = bin2hex(random_bytes(32));
    $hash = 'null';
  }
  else { 
    $access_token = 'null';
    $hash = password_hash($password, PASSWORD_DEFAULT);
  }

  $PDO_stm = $PDO -> 
  prepare(
  "INSERT INTO users VALUES 
    (
    null,
    :email,
    :username,
    '$hash',
    '$access_token',
    :ip,
    '$account_type',
    '$gender',
    '$r,$g,$b,$a',
    NOW()
    )"
  );

  $PDO_stm -> bindParam(':email', $email);
  $PDO_stm -> bindParam(':username', $username);
  $PDO_stm -> bindParam(':ip', $ip);
  if(!$PDO_stm -> execute())
    return [false, 'Nie udało się dodać użytkownika do bazy danych.'];

  if($account_type !== 'guest')
    return [true, true];

  $PDO_stm = $PDO -> query("SELECT id FROM users WHERE access_token = '$access_token'");
  $result = $PDO_stm -> fetch(PDO::FETCH_NUM);
  if(!$result[0])
    return [true, 'Dodano użytkownika, ale nie udało się zmienić nazwy.'];

  $PDO_stm = $PDO -> query("UPDATE users SET username = 'Gość $result[0]' WHERE access_token = '$access_token'");
  if(!$PDO_stm -> rowCount())
    return [true, 'Dodano użytkownika, ale nie udało się zmienić nazwy.(2)'];

  return [true, $result[0]];

  // może warto skorzystać z transakcji, żeby upewnić się, że nazwa zostanie zmieniona
}


