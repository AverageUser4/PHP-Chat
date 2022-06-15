<?php

declare(strict_types=1);

namespace PHP\Accounts;

use PHP\Global\Validator;
use PHP\Global\PDOConnection;
use PHP\Accounts\Loginer;
use \PDO;

class LoginValidator {

  private $PDO_connection;
  private $email;
  private $username;
  private $password;
  private $email_or_username_which;
  private $email_or_username_value;
  private $result;

  public function validate() {
    $this -> setUp();
    $this -> doDatabaseStuff();
    $this -> login();
    return true;
  }

  private function setUp() {
    if(!Validator::postExists(['username', 'password']))
    Validator::failureExit('Nie dostarczono wszytkich danych.');

    $this -> password = $_POST['password'];
    if(str_contains($_POST['username'], '@'))
      $this -> email = $_POST['username'];
    else $this -> username = $_POST['username'];

    if(isset($this -> email) && !Validator::validEmail($this -> email))
      Validator::failureExit('eWalidacja adresu e-mail nie powiodła się.');
    if(isset($this -> username) && !Validator::validUsername($this -> username))
      Validator::failureExit('uWalidacja loginu nie powiodła się.');

    $this -> email_or_username_which = 
      isset($this -> email) ? 'email' : 'username';
    $this -> email_or_username_value = 
      isset($this -> email) ? $this -> email : $this -> username;
  }

  private function doDatabaseStuff() {
    $this -> PDO_connection = new PDOConnection();
    $query = 
      "SELECT id, username, account_type, gender, color, password
      FROM users
      WHERE {$this -> email_or_username_which} = :email_or_username_value";

    $PDO_stm = $this -> PDO_connection -> PDO -> prepare($query);
    $PDO_stm -> bindParam(':email_or_username_value', $this -> email_or_username_value);
    $PDO_stm -> execute();
    $this -> result = $PDO_stm -> fetch(PDO::FETCH_ASSOC);

    if(
        !$this -> result
        || $this -> result['account_type'] === 'guest'
        || !password_verify($this -> password, $this -> result['password'])
      )
      Validator::failureExit("xNiepoprawne dane logowania.");

    if(isset($_POST['dont_logout'])) {
      $token = bin2hex(random_bytes(32));
      $query = 
        "UPDATE users 
         SET access_token = '$token'
         WHERE id = {$this -> result['id']}";

      if(
          $PDO_stm = $this -> PDO_connection -> PDO -> query($query)
          && $PDO_stm -> rowCount()
        )
        setcookie('access_token', $token, time() + 60*60*24*365, '/');
    }
  }

  private function login() {
    $loginer = new Loginer();
    $loginer -> login(
      $this -> result['id'],
      $this -> result['username'],
      $this -> result['account_type'],
      $this -> result['gender'],
      $this -> result['color']
    );
  }

}