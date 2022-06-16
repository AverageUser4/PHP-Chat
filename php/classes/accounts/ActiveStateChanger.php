<?php

declare(strict_types=1);

namespace PHP\Classes\Accounts;

use PHP\Classes\Global\PDOConnection;

class ActiveStateChanger {

  public static function switchUserActive(int $user_id, string $active_or_inactive) {  
    $aoi = $active_or_inactive === 'active' ? 1 : 0;
  
    $query = "UPDATE users SET active = $aoi WHERE id = $user_id";
  
    $PDO_connection = new PDOConnection();
    $PDO_connection -> PDO -> query($query);
  
    return true;
  }

}

