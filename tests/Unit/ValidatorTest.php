<?php

/*
setUp - metoda wywołana przed każdym testem
tearDown - metoda wykonana po każdym teście
data provider - do wykonania tego samego testu kilka razy z innymi wartościami
expectException - kiedy chcemy by metoda rzuciła wyjątek (trzeba użyć przed wywołaniem danej metody)
*/

namespace Tests\Unit;

use PHP\Global\Validator;
use PHPUnit\Framework\TestCase;
set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require 'php/global/Validator.php';

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
    return [ [['t1', 't2'], true], [['t1', 'doesnot'], false], [[], false] ];
  }

  /** @dataProvider validByteLengthDP */
  public function testValidByteLength($test_string, $min, $max, $expected) {
    $result = Validator::validByteLength($test_string, $min, $max);
    $this -> assertSame($expected, $result);
  }
  public function validByteLengthDP() {
    return [ 
      ['abcd', 1, 8, true],
      ['abcd', 4, 4, true],
      ['🐘🐘🐘', 2, 5, false],
      ['abcd', 5, 10, false],
      ['abcd', 1, 3, false]
   ];
  }

  /** @dataProvider validCharsDP */
  public function testValidChars($test_string, $expected) {
    $result = Validator::validChars($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validCharsDP() {
    return [ 
      ["abcdef123🐘", true],
      ["", false],
      ["  ", false], 
      ["abc\u{000c}def", false],
      ["abc\u{2028}def", false],
      ["abc\u{115f}def", false]
    ];
  }

  /** @dataProvider customEntitiesDP */
  public function testCustomEntities($test_string, $expected) {
    $result = Validator::customEntities($test_string);
    $this -> assertSame($expected, $result);
  }
  public function customEntitiesDP() {
    return [ 
      ['abc', 'abc'],
      ['%&"\'<>', '&#37;&#38;&#34;&#39;&#60;&#62;'],
      ['>x>', '&#62;x&#62;']
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
      ['adam', true],
      ['a', false],
      ["\u{180e}", false],
      ['GośćMega', false],
      ['abraham@gmail.com', false]
    ];
  }

  /** @dataProvider validGuestnameDP */
  public function testValidGuestname($test_string, $expected) {
    $result = Validator::validGuestname($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validGuestnameDP() {
    return [
      ['Gość 15', true],
      ['go', false],
      ["Gość 45\u{180e}", false],
      ['Gość11111', false],
      ['adran 505', false]
    ];
  }

  /** @dataProvider validPasswordDP */
  public function testValidPassword($test_string, $expected) {
    $result = Validator::validPassword($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validPasswordDP() {
    return [
      ['slicznykotek515🐈', true],
      ['sho', false],
      [str_repeat('a', 257), false]
    ];
  }

  /** @dataProvider validMessageDP */
  public function testValidMessage($test_string, $expected) {
    $result = Validator::validMessage($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validMessageDP() {
    return [
      ['ale super ten chat 👍', true],
      ['', false],
      [str_repeat('👍', 100), false],
      ["zakazany znak :) \u{180e}", false]
    ];
  }

  /** @dataProvider validAccessTokenDP */
  public function testValidAccessToken($test_string, $expected) {
    $result = Validator::validAccessToken($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validAccessTokenDP() {
    return [
      [str_repeat('a', 64), true],
      ['aaaaa', false],
      [str_repeat('a', 65), false],
      [str_repeat('x', 64), false]
    ];
  }

  /** @dataProvider validIntDP */
  public function testValidInt($test_int, $expected) {
    $result = Validator::validInt($test_int);
    $this -> assertSame($expected, $result);
  }
  public function validIntDP() {
    return [
      [123, true],
      ['123', true],
      [1.23, false],
      [null, false],
      ['2b27afc5ce31', false]
    ];
  }

  /** @dataProvider validColorDP */
  public function testValidColor($test_string, $expected) {
    $result = Validator::validColor($test_string);
    $this -> assertSame($expected, $result);
  }
  public function validColorDP() {
    return [
      ['0,0,0,0', true],
      ['255,255,255,1', true],
      ['1,2,5,0.3', true],
      ['515, 0, 32, 0', false],
      ['0,0,0,10', false],
      ['0,0', false],
      ['1.6,44,72,0.5', false],
      [',,,', false],
      ['a,b,c,d', false],
      ['-5,5,5,3', false],
      ['5,3,8,9,', false]
    ];
  }
  
  /** @dataProvider sanitizeGenderDP */
  public function testSanitizeGender($test_string, $expected) {
    $result = Validator::sanitizeGender($test_string);
    $this -> assertSame($expected, $result);
  }
  public function sanitizeGenderDP() {
    return [
      ['male', 'male'],
      ['female', 'female'],
      ['other', 'other'],
      ['fgdaf984', 'other']
    ];
  }
  
}