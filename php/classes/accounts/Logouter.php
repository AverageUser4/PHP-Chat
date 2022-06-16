<?php

declare(strict_types=1);

namespace PHP\Classes\Accounts;

use PHP\Classes\Accounts\ActiveStateChanger;

class Logouter {

  public function redirectToWeThankYou() {
    $location = 'Location: http://' . $_SERVER['SERVER_NAME'] .
    '/chat/html_or_php/we_thank_you.php';
    header($location);
  }

  public function logout() {
    session_start();
    $user_id = $_SESSION['id'];
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');
    setcookie('access_token', 'undef', time() + 60*60*24*365, '/');

    $active_state_changer = new ActiveStateChanger();
    $active_state_changer -> switchUserActive($user_id, 'inactive');
  }

}



