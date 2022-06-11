<?php

namespace Tests\Unit;

use Chat\PHP\Global\Validator;
use PHPUnit\Framework\TestCase;
set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require 'php/global/Validator.php';

class ValidatorTest extends TestCase {

  public function testGetExists() {
    // 2 of 2 exist
    $_GET['t1'] = 1;
    $_GET['t2'] = 2;
    $result = Validator::getExists(['t1', 't2']);
    $this -> assertTrue($result);

    // 1 of 2 exists
    $result = Validator::getExists(['t1', 'doesnot']);
    $this -> assertFalse($result);

    // array is empty
    $result = Validator::getExists([]);
    $this -> assertFalse($result);
  }

  public function testPostExists() {
    // 2 of 2 exist
    $_POST['t1'] = 1;
    $_POST['t2'] = 2;
    $result = Validator::postExists(['t1', 't2']);
    $this -> assertTrue($result);

    // 1 of 2 exists
    $result = Validator::postExists(['t1', 'doesnot']);
    $this -> assertFalse($result);

    // array is empty
    $result = Validator::postExists([]);
    $this -> assertFalse($result);
  }

  public function testValidByteLength() {
    // valid somewhere between
    $result = Validator::validByteLength('abcd', 1, 8);
    $this -> assertTrue($result);

    // valid equal to min and max
    $result = Validator::validByteLength('abcd', 4, 4);
    $this -> assertTrue($result);

    // invalid byte length, valid character length (so overall invalid)
    $result = Validator::validByteLength('🐘🐘🐘', 2, 5);
    $this -> assertFalse($result);

    // too short
    $result = Validator::validByteLength('abcd', 5, 10);
    $this -> assertFalse($result);

    // too long
    $result = Validator::validByteLength('abcd', 1, 3);
    $this -> assertFalse($result);
  }

  public function testValidChars() {
    // valid string gets validated
    $result = Validator::validChars("abcdef123🐘");
    $this -> assertTrue($result);

    // invalid: empty string
    $result = Validator::validChars("");
    $this -> assertFalse($result);

    // invalid: consists only of spaces
    $result = Validator::validChars("         ");
    $this -> assertFalse($result);

    // invalid: contains character from C group
    $result = Validator::validChars("abc\u{000c}def");
    $this -> assertFalse($result);

    // invalid: contains character from Z group
    $result = Validator::validChars("abc\u{2028}def");
    $this -> assertFalse($result);
    
    // invalid: contains one of specified invalid characters
    $result = Validator::validChars("abc\u{115f}def");
    $this -> assertFalse($result);
  }

  public function testCustomEntities() {
    // doesnt change a b or c
    $result = Validator::customEntities('abc');
    $this -> assertSame('abc', $result);

    // changes all of characters it should change
    $result = Validator::customEntities('%&"\'<>');
    $this -> assertSame('&#37;&#38;&#34;&#39;&#60;&#62;', $result);

    // changes character that occurs more than once
    $result = Validator::customEntities('>x>');
    $this -> assertSame('&#62;x&#62;', $result);
  }

  public function testValidEmail() {
    // valid email gets validated
    $result = Validator::validEmail('valid@email.good');
    $this -> assertTrue($result);

    // invalid email doen't get validated
    $result = Validator::validEmail('very invalid not email...');
    $this -> assertFalse($result);
  }

  public function testValidUsername() {
    // valid username gets validated
    $result = Validator::validUsername('adam');
    $this -> assertTrue($result);

    // invalid length
    $result = Validator::validUsername('a');
    $this -> assertFalse($result);

    // invalid characters (from valid chars)
    $result = Validator::validUsername('\u{180e}');
    $this -> assertFalse($result);

    // invalid: starts with 'Gość'
    $result = Validator::validUsername('GośćMega');
    $this -> assertFalse($result);

    // other invalid characters (specific to usernames)
    $result = Validator::validUsername('abraham@gmail.com');
    $this -> assertFalse($result);
  }

  public static function validUsername($user) {
    if(
      !valid_byte_length($user, 3, 32)
      || !valid_chars($user)
      || str_starts_with($user, 'Gość')
      || preg_match('/[!-.:-@[-`~]/', $user)
      )
      return false;
    return true;
  }

}
