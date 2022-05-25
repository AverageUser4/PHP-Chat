<?php

function valid_username($user) {
  if(
     str_starts_with($user, 'Gość')
    || str_contains($user, '@')
    )
    return false;
  return true;
}

function valid_guestname($guest) {
  if(
     !str_starts_with($guest, 'Gość ')
    || !filter_var(mb_substr($guest, 5), FILTER_VALIDATE_INT)
    )
    return false;
  return true;
}


var_dump(valid_username('Gość 45'));
var_dump(valid_guestname('Gość 45'));
echo mb_substr('Gość 55', 5);