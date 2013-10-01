<?php
class AllTestsTest extends CakeTestCase {
  public static function suite() {
    $suite = new CakeTestSuite();
    $suite->addTestDirectoryRecursive(APP . DS . 'Test' . DS . 'Case');
    return $suite;
  }
}
