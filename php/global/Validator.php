<?php

declare(strict_types=1);

namespace PHP\Global;

class Validator {

  const SAFE_CHARS_REGEX = "/[\p{C}\p{Z}\u{034f}\u{115f}\u{1160}\u{17b4}\u{17b5}\u{180e}\u{2800}\u{3164}\u{ffa0}]/u";

  public static function failureExit($e) { exit("error%$e"); }
  public static function failureReturn($e) { return "error%$e"; }

  public static function getExists(array $arr) {
    if(empty($arr))
      return false;

    foreach($arr as $x) {
      if(!isset($_GET[$x]))
        return false;
    }
    return true;
  }
  
  public static function postExists(array $arr) {
    if(empty($arr))
      return false;

    foreach($arr as $x) {
      if(!isset($_POST[$x]))
        return false;
    }
    return true;
  }
  
  public static function validByteLength(string $subject, int $min, int $max) {
    assert($min <= $max);
    assert($min >= 0);

    $len = strlen($subject);
    if($len < $min || $len > $max)
      return false;
    return true;
  }
  
  public static function validChars(string $subject) {
    if(
        preg_match_all(Validator::SAFE_CHARS_REGEX, $subject) > preg_match_all('/ /', $subject)
        || strlen(trim($subject)) === 0
      )
      return false;
    return true;
  }
  
  public static function customEntities(string $str) {
    //% is used as separator when sending message data
    $str = str_replace('&', '&#38;', $str);
    $str = str_replace('%', '&#37;', $str);
    $str = str_replace('"', '&#34;', $str);
    $str = str_replace("'", '&#39;', $str);
    $str = str_replace('<', '&#60;', $str);
    $str = str_replace('>', '&#62;', $str);
    return $str;
  }
  
  public static function validEmail(string $email) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
      return false;
    return true;
  }
  
  public static function validUsername(string $user) {
    if(
      !static::validByteLength($user, 3, 32)
      || !static::validChars($user)
      || str_starts_with($user, 'Gość')
      || preg_match('/[!-.:-@[-`~]/', $user)
      )
      return false;
    return true;
  }
  
  public static function validGuestname(string $guest) {
    if(
      !static::validByteLength($guest, 3, 32)
      || !static::validChars($guest)
      || !str_starts_with($guest, 'Gość ')
      || !filter_var(mb_substr($guest, 5), FILTER_VALIDATE_INT)
      )
      return false;
    return true;
  }
  
  public static function validPassword(string $password) {
    if(!static::validByteLength($password, 5, 256))
      return false;
    return true;
  }
  
  public static function validMessage(string $msg) {
    if(
      !static::validByteLength($msg, 1, 256)
      || !static::validChars($msg)
      )
      return false;
    return true;
  }
  
  public static function validAccessToken(string $token) {
    if(
      !static::validByteLength($token, 64, 64)
      || preg_match('/[^0-9a-f]/', $token)
      )
      return false;
    return true;
  }
  
  public static function validInt($int) {
    if(!filter_var($int, FILTER_VALIDATE_INT))
      return false;
    return true;
  }
  
  public static function validColor(string $color_str) {
    $arr = explode(',', $color_str);
    if(
      count($arr) !== 4
      || filter_var($arr[0], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 255]]) === false
      || filter_var($arr[1], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 255]]) === false
      || filter_var($arr[2], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 255]]) === false
      || filter_var($arr[3], FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 1]]) === false
      )
      return false;
    return true;
  }
  
  public static function sanitizeGender(string $gender) {
    if($gender === 'male')return 'male';
    if($gender === 'female')return 'female';
    return 'other';
  }

}