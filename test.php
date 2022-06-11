<?php

function validByteLength(string $subject, int $min, int $max) {
  assert($min <= $max);
  assert($min >= 0);

  $len = strlen($subject);
  if($len < $min || $len > $max)
    return false;
  return true;
}

var_dump(validByteLength('abc', -3, 0));