<?php

header('Location: ../../html_or_php/we_thank_you.php');
setcookie('access_token', 'undef', time() + 60*60*24*365, '/');

session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(), '', time() - 3600, '/');
session_regenerate_id(true);