<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHP\Messages\MessageSender;

class MessageSenderTest extends TestCase {

  private $message_sender;

  protected function setUp():void {
    parent::setUp();
    $this -> message_sender = new MessageSender();
  }

  /** @runInSeparateProcess */
  public function testInitialSetUpDoesNotCrash() {
    session_start();
    $_SESSION['id'] = 1;
    session_commit();
    $_GET['message'] = 'abcd';
    $result = $this -> message_sender -> initialSetUp();
    $this -> assertTrue($result);
  }

}