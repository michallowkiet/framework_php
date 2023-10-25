<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
  public function testIndex(): void {
    $_GET['name'] = 'Wladek';

    ob_start();
    include 'index.php';
    $content = ob_get_clean();

    $this->assertEquals('Hello Wladek', $content);
  }
}