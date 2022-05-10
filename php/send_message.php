<?php

function failure($error) { echo 'error%' . $error; exit(); }

function customEntities($str) {
  //% is used as separator when sending message data
  $str = str_replace('%', '&#37;', $str);
  $str = str_replace('&', '&#38;', $str);
  $str = str_replace('"', '&#34;', $str);
  $str = str_replace("'", '&#39;', $str);
  $str = str_replace('<', '&#60;', $str);
  $str = str_replace('>', '&#62;', $str);
  return $str;
}


/* user input validation */

if(!isset($_GET['user']) || !isset($_GET['message']))
  failure('No user and/or message provided.');

$user = customEntities($_GET['user']);
$msg = customEntities($_GET['message']);

if(
  mb_strlen($user, 'UTF-8') == 0
  || mb_strlen($user, 'UTF-8') > 32
  || mb_strlen(trim($user, ' '), 'UTF-8') == 0
  )
  failure('There is a problem with provided username.');

if(
  mb_strlen($msg, 'UTF-8') == 0
  || mb_strlen($msg, 'UTF-8') > 256
  || mb_strlen(trim($msg, ' '), 'UTF-8') == 0
  )
  failure('There is a problem with provided message.');


/* inserting input into the database */
  
$PDO = require_once 'pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$PDO_Statement = $PDO -> prepare('INSERT INTO messages VALUES (null, :username, :message, NOW())');
$PDO_Statement -> bindParam(':username', $user, PDO::PARAM_STR);
$PDO_Statement -> bindParam(':message', $msg, PDO::PARAM_STR);
if(!$PDO_Statement -> execute())
  failure('Database query did not succeed.');


echo '1';