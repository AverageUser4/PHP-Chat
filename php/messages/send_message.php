<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

session_start();
if(!isset($_SESSION['id']))
  failure('Użytkownik nie jest zalogowany.');
$user_id = $_SESSION['id'];
session_commit();
  
/* user input validation */
if(!isset($_GET['message']))
  failure('No message provided.');

$msg = customEntities($_GET['message']);

if(!valid_message($msg))
  failure('There is a problem with provided message.');


/* insert message into the database */
require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$PDO_Statement = $PDO -> prepare("INSERT INTO messages VALUES (null, $user_id, :message, NOW())");
$PDO_Statement -> bindParam(':message', $msg, PDO::PARAM_STR);
if(!$PDO_Statement -> execute())
  failure('Database query did not succeed.');


echo '1';