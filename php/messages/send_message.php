<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';


/* user input validation */

if(!get_exists(['user', 'message', 'guest_token', 'guest_id']))
  failure('No user, token, id and/or message provided.');

$user = customEntities($_GET['user']);
$msg = customEntities($_GET['message']);
$guest_token = $_GET['guest_token'];
$id = $_GET['guest_id'];

if(!valid_username($user))
  failure('There is a problem with provided username.');

if(!valid_message($msg))
  failure('There is a problem with provided message.');

if(!valid_guest_token($guest_token))
  failure('There is a problem with provided guest token.');

if(!valid_int($id))
  failure('There is a problem with provided id.');


/* check if user exists */

require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$PDO_Statement = $PDO -> prepare('SELECT id FROM guests WHERE id = :id AND token = :token');
$PDO_Statement -> bindParam(':id', $id, PDO::PARAM_INT);
$PDO_Statement -> bindParam(':token', $guest_token, PDO::PARAM_STR);
$PDO_Statement -> execute();
if(!$PDO_Statement -> fetch())
  failure('No such user in the database!!!');
  
/* insert message into the database */

$PDO_Statement = $PDO -> prepare('INSERT INTO messages VALUES (null, :username, :message, NOW())');
$PDO_Statement -> bindParam(':username', $user, PDO::PARAM_STR);
$PDO_Statement -> bindParam(':message', $msg, PDO::PARAM_STR);
if(!$PDO_Statement -> execute())
  failure('Database query did not succeed.');


echo '1';