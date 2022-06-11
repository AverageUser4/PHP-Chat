<?php

header('Location: ../../html_or_php/we_thank_you.php');
setcookie('access_token', 'undef', time() + 60*60*24*365, '/');

set_include_path($_SERVER['DOCUMENT_ROOT'] . '/chat/php');
require_once 'accounts/reusable.php';
makeUserActiveOrInactive('inactive');

session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(), '', time() - 3600, '/');