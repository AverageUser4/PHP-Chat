<?php

set_time_limit(1);
set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

if(!post_exists(['username', 'password']))
  failure('Nie dostarczono wszystkich danych.');


$password = $_POST['password'];

if(str_contains($_POST['username'], '@'))
  $email = $_POST['username'];
else $username = $_POST['username'];

if(isset($email) && !valid_email($email))
  failure('eWalidacja adresu e-mail nie powiodła się.');
if(isset($username) && !valid_username($username))
  failure('uWalidacja loginu nie powiodła się.');

$email_or_username = isset($email) ? 'email' : 'username';
$em_or_us_val = isset($email) ? $email : $username;


require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$query = 
"SELECT id, username, account_type, gender, color, password
FROM users WHERE $email_or_username = :em_or_us_val";

$PDO_stm = $PDO -> prepare($query);
$PDO_stm -> bindParam(':em_or_us_val', $em_or_us_val);
$PDO_stm -> execute();
$result = $PDO_stm -> fetch(PDO::FETCH_ASSOC);

if(
    !$result
    || $result['account_type'] === 'guest'
    || !password_verify($password, $result['password'])
  )
  failure("xNiepoprawne dane logowania.");


if(isset($_POST['dont_logout'])) {
  $token = bin2hex(random_bytes(32));
  $query = "UPDATE users SET access_token = '$token' WHERE id = {$result['id']}";
  if($PDO_stm = $PDO -> query($query) && $PDO_stm -> rowCount())
    setcookie('access_token', $token, time() + 60*60*24*365, '/');
}


session_start();
$_SESSION['id'] = $result['id'];
$_SESSION['username'] = $result['username'];
$_SESSION['account_type'] = $result['account_type'];
$_SESSION['gender'] = $result['gender'];
$_SESSION['color'] = $result['color'];
session_commit();

echo '1';