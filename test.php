<?php

declare(strict_types=1);

    // username, message and date
    // for($i = 0; $i < $len; $i++) {
    //   $messages_string .= $this -> query_result[$i]['username'] .= '%';
    //   $messages_string .= $this -> query_[$i]['content'] .= '%';
    //   $messages_string .= $this -> query_[$i]['date'];
    //   $i != $len - 1 ? $messages_string .= '%' : 0;

$test = new class {
  public $metadata = ['abc', 'def', 7];
  public $messagedata = [['user1', 'bekaxd', '2020-03-03'], ['user2', 'xd', '2020-03-03']];
};

var_dump(json_encode($test));