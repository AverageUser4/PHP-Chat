<?php

$location = $_SERVER['DOCUMENT_ROOT'] . '/chat/html_or_php/something_went_wrong.php';
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

function post_exists(array $arr) {
  foreach($arr as $x) {
    if(!isset($_POST[$x]))
      return false;
  }
  return true;
}

function valid_byte_length($subject, $min, $max) {
  $len = strlen($subject);
  if($len < $min || $len > $max)
    return false;
  return true;
}

function valid_chars($subject) {
  global $safe_chars_regex;
  if(
      preg_match_all($safe_chars_regex, $subject) > preg_match_all('/ /', $subject)
      || strlen(trim($subject)) === 0
    )
    return false;
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

function valid_email($email) {
  if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    return false;
  return true;
}

function valid_username($user) {
  if(
    !valid_byte_length($user, 3, 32)
    || !valid_chars($user)
    || str_starts_with($user, 'Gość')
    || preg_match('/[!-.:-@[-`~]/', $user)
    )
    return false;
  return true;
}

function valid_guestname($guest) {
  if(
    !valid_byte_length($guest, 3, 32)
    || !valid_chars($guest)
    || !str_starts_with($guest, 'Gość ')
    || !filter_var(mb_substr($guest, 5), FILTER_VALIDATE_INT)
    )
    return false;
  return true;
}

function valid_password($password) {
  if(!valid_byte_length($password, 5, 256))
    return false;
  return true;
}

function valid_message($msg) {
  if(
    !valid_byte_length($msg, 1, 256)
    || !valid_chars($msg)
    )
    return false;
  return true;
}

function valid_access_token() {
  $token = $_COOKIE['access_token'] ?? null;
  if(
    is_null($token)
    || !valid_byte_length($token, 64, 64)
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
    || (!$arr[0] == 0 && !filter_var($arr[0], FILTER_VALIDATE_INT, ['min_range' => 0, 'max_range' => 255]))
    || (!$arr[1] == 0 && !filter_var($arr[1], FILTER_VALIDATE_INT, ['min_range' => 0, 'max_range' => 255]))
    || (!$arr[2] == 0 && !filter_var($arr[2], FILTER_VALIDATE_INT, ['min_range' => 0, 'max_range' => 255]))
    || (!$arr[3] == 0 && !filter_var($arr[3], FILTER_VALIDATE_FLOAT, ['min_range' => 0, 'max_range' => 1]))
    )
    return false;
  return true;
}

function sanitize_gender($gender) {
  if($gender === 'male')return 'male';
  if($gender === 'female')return 'female';
  return 'other';
}