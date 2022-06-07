<?php


set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'global/pdo_connect.php';


function makeUserActiveOrInactive($active_or_inactive) {
  global $PDO;
  if(!$PDO instanceof PDO)
    return false;

  session_start();
  if(!isset($_SESSION['id'])) {
    session_commit();
    return false;
  }
  $id = $_SESSION['id'];
  session_commit();

  $aoi = $active_or_inactive === 'active' ? 1 : 0;

  $query = "UPDATE users SET active = $aoi WHERE id = $id";
  $PDO_stm = $PDO -> query($query);

  //if(!$PDO_stm -> rowCount())
    //log maybe

  return true;
}

var_dump(makeUserActiveOrInactive('inactive'));