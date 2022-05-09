<?php

function failure($err) { echo $err; exit(); }

if(
  !isset($_GET['user'])
  || strlen($_GET['user']) == 0
  || strlen($_GET['user']) > 32
  || strlen(trim($_GET['user'], ' ')) == 0
  )
  failure('bad user');

if(
  !isset($_GET['message'])
  || strlen($_GET['message']) == 0
  || strlen($_GET['message']) > 256
  || strlen(trim($_GET['message'], ' ')) == 0
  )
  failure('bad message');


$user = str_replace('%', '&#37;', htmlentities($_GET['user'], ENT_QUOTES));
$msg = str_replace('%', '&#37;', htmlentities($_GET['message'], ENT_QUOTES));

$PDO = require_once 'pdo_connect.php';

try {
  if(!$PDO instanceof PDO)
    throw new Exception('connection not established');

  $PDO_Statement = $PDO -> prepare('INSERT INTO messages VALUES (null, :username, :message, NOW())');
  $PDO_Statement -> bindParam(':username', $user, PDO::PARAM_STR);
  $PDO_Statement -> bindParam(':message', $msg, PDO::PARAM_STR);
  if(!$PDO_Statement -> execute())
    throw new Exception('database query failure');

} catch(Exception $e) { failure($e); }

echo 'true';