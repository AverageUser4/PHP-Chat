<?php

set_time_limit(3);
set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');

function guc_shutdown_handler() {
  if(connection_aborted())
    return;

  if($e = error_get_last())
    if(str_contains($e['message'], 'Maximum execution time'))
      failure('Przekroczono limit czasu.');
}
register_shutdown_function('guc_shutdown_handler');

require_once 'global/validate.php';

if(!isset($_GET['username']) || !valid_username($_GET['username']))
  failure('Niepoprawna nazwa użytkownika.');

$id = explode(' ', $_GET['username']);
if(!is_array($id) || !isset($id[1]) || !filter_var($id[1], FILTER_VALIDATE_INT))
  failure('Nazwa gościa nie zawiera cyfry.');
$id = $id[1];

require_once 'global/pdo_connect.php';
if(!$PDO instanceof PDO)
  failure($PDO);

$PDO_stm = $PDO -> prepare('SELECT color FROM guests WHERE id = :id');
$PDO_stm -> bindParam(':id', $id, PDO::PARAM_INT);
$PDO_stm -> execute();
$result = $PDO_stm -> fetch(PDO::FETCH_NUM);
if(!$result)
  failure('W bazie danych nie ma gościa o podanym ID.');

echo $result[0];

/* 
currently you can give any nickname with digit after space and it will work
if there's adequate ID in the database
*/
