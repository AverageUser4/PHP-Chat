<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

if(!isset($_GET['color']))
  failure('Nie wysłano koloru.');
if(!valid_guest_token())
  failure('Niepoprawny token.');


require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$PDO_stm = $PDO -> prepare("UPDATE guests SET color = :color WHERE token = :token");
$PDO_stm -> bindParam(':color', $_GET['color']);
$PDO_stm -> bindParam(':token', $_COOKIE['guest_token'], PDO::PARAM_STR);
if(!$PDO_stm -> execute())
  failure('Nie udało się dodać koloru do bazy danych.');

echo '1';