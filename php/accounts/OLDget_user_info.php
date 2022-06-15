<?php

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/validate.php';

function gui_shutdown_handler() {
  if(connection_aborted())
    return;

  if($e = error_get_last())
    if(str_contains($e['message'], 'Maximum execution time'))
      failure('Przekroczono limit czasu.');
    else
      failure('Nieoczekiwany błąd.');
}
register_shutdown_function('gui_shutdown_handler');

$user = $_GET['username'] ?? null;

if(
  !isset($user)
  || (!valid_username($user) && !valid_guestname($user))
  )
  failure('Niepoprawna nazwa użytkownika.');


require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$query_string = 
'SELECT account_type, gender, color FROM users 
WHERE username = :username';

$PDO_stm = $PDO -> prepare($query_string);
$PDO_stm -> bindParam(':username', $user, PDO::PARAM_STR);
$PDO_stm -> execute();
$result = $PDO_stm -> fetch(PDO::FETCH_NUM);
if(!$result)
  failure('W bazie danych nie ma gościa o podanym ID.');

echo "{ \"account_type\": \"$result[0]\", \"gender\": \"$result[1]\", \"color\": \"$result[2]\" }";
