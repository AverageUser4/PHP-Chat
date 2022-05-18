<?php

function failure($e) { echo 'error%' . $e; exit(); }
function failure_return($e) { return 'error%' . $e; }

function get_exists(array $arr) {
  foreach($arr as $x) {
    if(!isset($_GET[$x]))
      return false;
  }
  return true;
}

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

function valid_username($user) {
  if(
    mb_strlen($user, 'UTF-8') == 0
    || mb_strlen($user, 'UTF-8') > 32
    || mb_strlen(trim($user, ' '), 'UTF-8') == 0
    )
    return false;
  return true;
}

function valid_message($msg) {
  if(
    mb_strlen($msg, 'UTF-8') == 0
    || mb_strlen($msg, 'UTF-8') > 256
    || mb_strlen(trim($msg, ' '), 'UTF-8') == 0
    )
    return false;
  return true;
}

function valid_guest_token($guest_token) {
  if(
    mb_strlen($guest_token, 'UTF-8') != 64
    || !ctype_alnum($guest_token)
    )
    return false;
  return true;
}

function valid_int($int) {
  if(!filter_var($int, FILTER_VALIDATE_INT, 
    [FILTER_FLAG_ALLOW_HEX => false, FILTER_FLAG_ALLOW_OCTAL => false])
    )
    return false;
  return true;
}