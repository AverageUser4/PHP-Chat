<?php

declare(strict_types=1);
namespace PHP\Accounts;

use PHP\Global\PDOConnection;
use \PDO;

class GuestCreator {

  protected $PDO_connection;
  protected $color_string;
  protected $hash;
  protected $access_token;
  protected $first_PDO_stm;

  public function __construct
  (
    protected $email = 'undef',
    protected $username = 'undef',
    protected $password = 'undef',
    protected $gender = 'other',
    protected $account_type = 'guest',
    protected $user_id = 'undef'
  ) {
    $this -> PDO_connection = new PDOConnection();
  }

  protected function setRandomColorString() {
    $r = random_int(0, 255);
    $g = random_int(0, 255);
    $b = random_int(0, 255);
    $a = random_int(3, 6) / 10;
    $this -> color_string = "$r,$g,$b,$a";
  }

  protected function setUpPDOStatement() {
    $this -> first_PDO_stm = $this -> PDO_connection -> PDO -> prepare(
      "INSERT INTO users VALUES 
      (
      NULL,
      :email,
      :username,
      :hash,
      :access_token,
      :account_type,
      :gender,
      :color_string,
      NOW(),
      0
      )"
    );
    $this -> first_PDO_stm -> bindParam(':email', $this -> email, PDO::PARAM_STR);
    $this -> first_PDO_stm -> bindParam(':username', $this -> username, PDO::PARAM_STR);
    $this -> first_PDO_stm -> bindParam(':hash', $this -> hash, PDO::PARAM_STR);
    $this -> first_PDO_stm -> bindParam(':access_token', $this -> access_token, PDO::PARAM_STR);
    $this -> first_PDO_stm -> bindParam(':account_type', $this -> account_type, PDO::PARAM_STR);
    $this -> first_PDO_stm -> bindParam(':gender', $this -> gender, PDO::PARAM_STR);
    $this -> first_PDO_stm -> bindParam(':color_string', $this -> color_string, PDO::PARAM_STR);
  }

  public function insertNewGuest() {
    $this -> setUpPDOStatement();
    $this -> setRandomColorString();
    $this -> access_token = bin2hex(random_bytes(32));
    $this -> hash = 'undef';
    $this -> setUpPDOStatement();
    
    $this -> PDO_connection -> PDO -> beginTransaction();
    if(!$this -> first_PDO_stm -> execute())
      return [false, 'Nie udało się dodać użytkownika do bazy danych.'];

    $PDO_stm = $this -> PDO_connection -> PDO -> query(
      "SELECT id FROM users WHERE access_token = '{$this -> access_token}'"
    );
    $result = $PDO_stm -> fetch(PDO::FETCH_NUM);
    if(!$result[0]) {
      $this -> PDO_connection -> PDO -> rollBack();
      return [false, 'Dodano użytkownika, ale nie udało się zmienić nazwy.'];
    }

    $PDO_stm = $this -> PDO_connection -> PDO -> query(
      "UPDATE users SET username = 'Gość $result[0]' WHERE access_token = '{$this -> access_token}'"
    );
    if(!$PDO_stm -> rowCount()) {
      $this -> PDO_connection -> PDO -> rollBack();
      return [false, 'Dodano użytkownika, ale nie udało się zmienić nazwy.(2)'];
    }

    $this -> PDO_connection -> PDO -> commit();
    setcookie('access_token', $this -> access_token, time() + 60*60*24*365, '/');
    $_COOKIE['access_token'] = $this -> access_token;
    return [true, $result[0]];
  }

}