<?php

header('Location: ../../html_or_php/we_thank_you.php');

session_start();
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(),'',0,'/');
session_regenerate_id(true);