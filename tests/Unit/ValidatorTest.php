<?php

/*
setUp - metoda wywołana przed każdym testem
tearDown - metoda wykonana po każdym teście
data provider - do wykonania tego samego testu kilka razy z innymi wartościami
expectException - kiedy chcemy by metoda rzuciła wyjątek (trzeba użyć przed wywołaniem danej metody)
*/

namespace Tests\Unit;

use PHP\Classes\Global\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase {

  /** @dataProvider getExistsAndPostExistsDP */
  public function testGetExists($test_array, $expected) {
    $_GET['t1'] = 1;
    $_GET['t2'] = 2;
    $result = Validator::getExists($test_array);
    $this -> assertSame($expected, $result);
  }
  /** @dataProvider getExistsAndPostExistsDP */
  public function testPostExists($test_array, $expected) {
    $_POST['t1'] = 1;
    $_POST['t2'] = 2;
    $result = Validator::postExists($test_array);
    $this -> assertSame($expected, $result);
  }
  public function getExistsAndPostExistsDP() {
    return [ 
      'first and second exists' => [['t1', 't2'], true],
      'only first exists' => [['t1', 'doesnot'], false],
      'only second exists' => [['doestnot', 't2'], false],
      'array is empty' => [[], false]
    ];
  }

  /** @dataProvider validByteLengthDP */
  public function testValidByteLength($test_string, $min, $max, $expected) {
    $result = Validator::validByteLength($test_string, $min, $max);
    $this -> assertSame($expected, $result);
  }
  public function validByteLengthDP() {
    return [ 
      'valid length' => ['abcd', 1, 8, true],
      'valid identical length' => ['abcd', 4, 4, true],
      'too long multibyte string' => ['🐘🐘🐘', 2, 5, false],
      'too short' => ['abcd', 5, 10, false],
      'too long' => ['abcd', 1, 3, false]
   ];
  }

  /** @dataProvider validCharsDP */
  public function testValidChars($test_string, $expected) {
    $result = Validator::validChars($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validCharsDP() {
    return [ 
      'valid with special character' => ["abcdef123🐘", true],
      'empty string' => ["", false],
      'only spaces' => ["  ", false], 
      'invalid character C group' => ["abc\u{000c}def", false],
      'invalid character Z' => ["abc\u{2028}def", false],
      'invalid character other' => ["abc\u{115f}def", false]
    ];
  }

  /** @dataProvider customEntitiesDP */
  public function testCustomEntities($test_string, $expected) {
    $result = Validator::customEntities($test_string);
    $this -> assertSame($expected, $result);
  }
  public function customEntitiesDP() {
    return [ 
      'normal string' => ['abc', 'abc'],
      'all characters to be chganged' => ['%&"\'<>', '&#37;&#38;&#34;&#39;&#60;&#62;'],
      'same character multiple times' => ['>x>>', '&#62;x&#62;&#62;']
    ];
  }

  public function testValidEmail() {
    $result = Validator::validEmail('valid@email.good');
    $this -> assertTrue($result);
    $result = Validator::validEmail('very invalid not email...');
    $this -> assertFalse($result);
  }

  /** @dataProvider validUsernameDP */
  public function testValidUsername($test_string, $expected) {
    $result = Validator::validUsername($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validUsernameDP() {
    return [ 
      'valid username' => ['adam', true],
      'too short' => ['a', false],
      'contains invalid character' => ["abcder\u{180e}", false],
      'starts with Gość' => ['GośćMega', false],
      'contains invalid character (@)' => ['abraham@gmail.com', false]
    ];
  }

  /** @dataProvider validGuestnameDP */
  public function testValidGuestname($test_string, $expected) {
    $result = Validator::validGuestname($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validGuestnameDP() {
    return [
      'valid guest name' => ['Gość 15', true],
      'too short' => ['go', false],
      'contains invalid character' => ["Gość 45\u{180e}", false],
      'does not contain space' => ['Gość11111', false],
      'does not start with Gość but contains space and number' => ['adrian 505', false]
    ];
  }

  /** @dataProvider validPasswordDP */
  public function testValidPassword($test_string, $expected) {
    $result = Validator::validPassword($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validPasswordDP() {
    return [
      'valid password' => ['slicznykotek515🐈', true],
      'too short' => ['sho', false],
      'too long' => [str_repeat('a', 257), false]
    ];
  }

  /** @dataProvider validMessageDP */
  public function testValidMessage($test_string, $expected) {
    $result = Validator::validMessage($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validMessageDP() {
    return [
      'valid ascii message' => ['abc123', true], 
      'valid message with emoji' => ['ale super ten chat 👍', true],
      'empty message' => ['', false],
      'too long with multibyte characters' => [str_repeat('👍', 100), false],
      'contains invalid character' => ["zakazany znak :) \u{180e}", false]
    ];
  }

  /** @dataProvider validAccessTokenDP */
  public function testValidAccessToken($test_string, $expected) {
    $result = Validator::validAccessToken($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validAccessTokenDP() {
    return [
      'theoretically valid token' => [str_repeat('a', 64), true],
      'valid chars, too short' => ['aaaaa', false],
      'valid chars, too long' => [str_repeat('a', 65), false],
      'invalid chars, valid length' => [str_repeat('x', 64), false]
    ];
  }

  /** @dataProvider validIntDP */
  public function testValidInt($test_int, $expected) {
    $result = Validator::validInt($test_int);
    $this -> assertSame($expected, $result);
  }
  public function validIntDP() {
    return [
      'normal valid int' => [123, true],
      'valid string to be converted in int' => ['123', true],
      'invalid float' => [1.23, false],
      'invalid null' => [null, false],
      'invalid base16' => ['2b27afc5ce31', false]
    ];
  }

  /** @dataProvider validColorDP */
  public function testValidColor($test_string, $expected) {
    $result = Validator::validColor($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validColorDP() {
    return [
      'only zeros (minimal accepted values)' => ['0,0,0,0', true],
      'maximal accepted values' => ['255,255,255,1', true],
      'random valid values' => ['1,2,5,0.3', true],
      'one int too big' => ['515, 0, 32, 0', false],
      'float to big' => ['0,0,0,10', false],
      'only two values' => ['0,0', false],
      'float where int expected' => ['1.6,44,72,0.5', false],
      'only commas with no values' => [',,,', false],
      'letters instead of numbers' => ['a,b,c,d', false],
      'negative number on int place' => ['5,-5,5,3', false],
      'negative number on float place' => ['5,5,5,-1', false],
      'trailing comma' => ['5,3,8,0,', false]
    ];
  }
  
  /** @dataProvider sanitizeGenderDP */
  public function testSanitizeGender($test_string, $expected) {
    $result = Validator::sanitizeGender($test_string);
    $this -> assertSame($expected, $result);
  }
  public function sanitizeGenderDP() {
    return [
      'male given expected' => ['male', 'male'],
      'female given and expected' => ['female', 'female'],
      'other given adn expected' => ['other', 'other'],
      'random string given and other expected' => ['fgdaf984', 'other']
    ];
  }
  
}