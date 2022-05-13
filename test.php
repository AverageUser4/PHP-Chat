<?php


function abc() {
  $e = error_get_last();
  echo str_contains($e['message'], 'Maximum execution time');
}

register_shutdown_function('abc');

set_time_limit(1);

while(1) {
  $a = 3;
}