<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

session_start();
if(!isset($_SESSION['id']))
  failure('Użytkownik nie jest zalogowany.');
$id = $_SESSION['id'];
session_commit();

if(!isset($_GET['color']))
  failure('Nie wysłano koloru.');
if(!valid_color($_GET['color']))
  failure('Podany kolor jest niepoprawny.');

require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$PDO_stm = $PDO -> prepare("UPDATE users SET color = :color WHERE id = $id");
$PDO_stm -> bindParam(':color', $_GET['color']);
if(!$PDO_stm -> execute())
  failure('Nie udało się dodać koloru do bazy danych.');

echo '1';