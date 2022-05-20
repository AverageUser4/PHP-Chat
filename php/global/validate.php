<?php

$safe_chars_regex = "/[\p{C}\p{Z}\u{034f}\u{115f}\u{1160}\u{17b4}\u{17b5}\u{180e}\u{2800}\u{3164}\u{ffa0}]/u";

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
  global $safe_chars_regex;
  if(
    mb_strlen($user, 'UTF-8') == 0
    || mb_strlen($user, 'UTF-8') > 32
    || preg_match_all($safe_chars_regex, $user) > preg_match_all('/ /', $user)
    || strlen(trim($user)) === 0
    )
    return false;
  return true;
}

function valid_message($msg) {
  global $safe_chars_regex;
  if(
    mb_strlen($msg, 'UTF-8') == 0
    || mb_strlen($msg, 'UTF-8') > 256
    || preg_match_all($safe_chars_regex, $msg) > preg_match_all('/ /', $msg)
    || strlen(trim($msg)) === 0
    )
    return false;
  return true;
}

function valid_guest_token() {
  $token = $_COOKIE['guest_token'] ?? null;
  if(
    is_null($token)
    || mb_strlen($token, 'UTF-8') != 64
    || !ctype_alnum($token)
    )
    return false;
  return true;
}

function valid_int($int) {
  if(!filter_var($int, FILTER_VALIDATE_INT))
    return false;
  return true;
}

function valid_color($color_str) {
  $arr = explode(',', $color_str);
  if(
    count($arr) !== 4
    || !filter_var($arr[0], FILTER_VALIDATE_INT, ['min_range' => 0, 'max_range' => 255])
    || !filter_var($arr[1], FILTER_VALIDATE_INT, ['min_range' => 0, 'max_range' => 255])
    || !filter_var($arr[2], FILTER_VALIDATE_INT, ['min_range' => 0, 'max_range' => 255])
    || !filter_var($arr[3], FILTER_VALIDATE_FLOAT, ['min_range' => 0, 'max_range' => 1])
    )
    return false;
  return true;
}