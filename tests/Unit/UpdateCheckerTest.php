<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHP\Messages\UpdateChecker;

class UpdateCheckerTest extends TestCase {

  private $update_checker;

  protected function setUp():void {
    parent::setUp();
    $this -> update_checker = new UpdateChecker();
  }

  /** @runInSeparateProcess */
  public function testInitialSetUpDoesNotCrash() {
    session_start();
    $_SESSION['id'] = 1;
    session_commit();
    $_GET['latest'] = 3;
    $result = $this -> update_checker -> initialSetUp();
    $this -> assertTrue($result);
  }

}