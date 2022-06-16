<?php

use PHP\Classes\Accounts\ProfileCustomizer;

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat');
require_once 'vendor/autoload.php';

$profile_customizer = new ProfileCustomizer();
if($profile_customizer -> customize())
  echo '1';