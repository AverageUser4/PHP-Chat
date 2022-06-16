<?php

declare(strict_types=1);

namespace PHP\Classes\Accounts;

use PHP\Classes\Accounts\UserCreator;
use PHP\Classes\Global\Validator;

class RegisterValidator {

  private $user_id;
  private $email;
  private $username;
  private $password;
  private $gender;

  public function createNewAccount() {
    $this -> setUp();
    $this -> create();
    return true;
  }

  private function setUp() {
    if(!Validator::postExists(['email', 'username', 'password', 'gender']))
      Validator::failureExit('Nie dostarczono wszystkich danych.');
      
    $this -> email = $_POST['email'];
    $this -> username = $_POST['username'];
    $this -> password = $_POST['password'];
    $this -> gender = Validator::sanitizeGender($_POST['gender']);

    if(!Validator::validEmail($this -> email))
      Validator::failureExit('eWalidacja adresu e-mail nie powiodła się.');
    if(!Validator::validUsername($this -> username))
      Validator::failureExit('uWalidacja loginu nie powiodła się.');
    if(!Validator::validPassword($this -> password))
      Validator::failureExit('pWalidacja hasła nie powiodła się. (min. 5, max. 256 znaków)');
  }

  private function create() {
    session_start();
    $this -> user_id = $_SESSION['id'] ?? 'undef';
    session_commit();
    
    $user_creator = new UserCreator(
      $this -> email,
      $this -> username,
      $this -> password,
      $this -> gender,
      'user', $this -> user_id
    );

    if($this -> user_id === 'undef') {
      $success = $user_creator -> insertNewUser();
      if(!$success[0])
        Validator::failureExit($success[1]);
    }
    else {
      $success = $user_creator -> changeGuestToUser();
      if(!$success[0]) {
        $success = $user_creator -> insertNewUser();
      if(!$success[0])
        Validator::failureExit($success[1]);
      }
    }
  }

}




