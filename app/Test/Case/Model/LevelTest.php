<?php
class LevelTest extends CakeTestCase {
  public function setUp() {
    $this->Level = ClassRegistry::init('Level');
    $this->Auth = ClassRegistry::init('AuthComponent');
  }

  public function testRating() {
    $this->Level->create();
    $this->Level->save();
    $this->assertEquals(0, $this->Level->read('rating'));
  }
}
?>
