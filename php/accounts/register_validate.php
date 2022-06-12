<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

if(!post_exists(['email', 'username', 'password', 'gender']))
  failure('Nie dostarczono wszystkich danych.');

$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$gender = sanitize_gender($_POST['gender']);

if(!valid_email($email))
  failure('eWalidacja adresu e-mail nie powiodła się.');
if(!valid_username($username))
  failure('uWalidacja loginu nie powiodła się.');
if(!valid_password($password))
  failure('pWalidacja hasła nie powiodła się. (min. 5, max. 256 znaków)');

$ip = 'null';
if(
  isset($_SERVER['REMOTE_ADDR'])
  && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)
)
$ip = $_SERVER['REMOTE_ADDR'];


require_once 'accounts/reusable.php';
session_start();
$user_id = $_SESSION['id'] ?? null;
session_commit();

if(!$user_id) {
  $success = insert_new_user($ip, $email, $username, $password, $gender, 'user');
  if(!$success[0])
    failure($success[1]);
}
else {
  $success = change_guest_to_user($email, $username, $password, $gender, $user_id);
  if(!$success[0]) {
    $success = insert_new_user($ip, $email, $username, $password, $gender, 'user');
  if(!$success[0])
    failure($success[1]);
  }
}


echo '1';
