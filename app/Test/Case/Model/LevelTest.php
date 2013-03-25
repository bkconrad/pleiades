<?php
App::uses('Level', 'Model');
App::uses('Rating', 'Model');
class LevelTest extends CakeTestCase {
  public function setUp() {
    parent::setUp();
    $this->Level = ClassRegistry::init('Level');
    $this->Level->deleteAll(true);
    $this->Rating = ClassRegistry::init('Rating');
    $this->Rating->deleteAll(true);
    $this->Auth = ClassRegistry::init('AuthComponent');
  }

  public function testRating() {
    // starts at zero
    $this->Level->create();
    $this->assertEquals(0, $this->Level->field('rating'));

    // can be negative
    $this->Level->rate(1, -1);
    $this->assertEquals(-1, $this->Level->field('rating'));

    // replaces old ratings
    $this->Level->rate(1, 1);
    $this->assertEquals(1, $this->Level->field('rating'));

    // accumlates for each level
    $this->Level->rate(2, 1);
    $this->assertEquals(2, $this->Level->field('rating'));
  }

  public function testLevelgenRequirement() {
    $this->Level->create();

    $noLineNoLevelgen = array(
      'name' => 'level',
      'content' => 'empty (more or less)',
      'levelgen' => '',
      'description' => 'descriptive'
    );

    $noLineYesLevelgen = array(
      'name' => 'level',
      'content' => 'empty (more or less)',
      'levelgen' => 'test',
      'description' => 'descriptive'
    );

    $yesLineNoLevelgen = array(
      'name' => 'level',
      'content' => 'Script test.levelgen',
      'levelgen' => '',
      'description' => 'descriptive'
    );

    $yesLineYesLevelgen = array(
      'name' => 'level',
      'content' => 'Script test.levelgen',
      'levelgen' => 'foo',
      'description' => 'descriptive'
    );

    $this->assertFalse(!!$this->Level->save($noLineYesLevelgen));
    $this->assertTrue(!!$this->Level->save($noLineNoLevelgen));
    $this->assertFalse(!!$this->Level->save($yesLineNoLevelgen));
    $this->assertTrue(!!$this->Level->save($yesLineYesLevelgen));
  }
}
?>
