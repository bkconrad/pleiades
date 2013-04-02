<?php
App::import('Model', 'Level');
App::import('Model', 'Rating');
class LevelTest extends CakeTestCase {
  public $fixtures = array('app.level', 'app.user', 'app.rating');
  public function setUp() {
    parent::setUp();
    $this->Level = ClassRegistry::init('Level');
    $this->Rating = ClassRegistry::init('Rating');
  }

  public function testRating() {
    $this->Level->create();
    // requires $this->Level->id to be set
    $this->assertFalse($this->Level->rate(1, -1));

    $this->Level->save(array(
      'name' => 'level',
      'content' => 'LevelName foo',
      'levelgen' => '',
      'description' => 'descriptive'
    ));
    $this->Level->find('first');

    // starts at zero
    $this->assertEquals(0, $this->Level->field('rating'));

    // can be negative
    $this->assertTrue($this->Level->rate(1, -1));
    $this->assertEquals(-1, $this->Level->field('rating'));

    // replaces old ratings
    $this->assertTrue($this->Level->rate(1, 1));
    $this->assertEquals(1, $this->Level->field('rating'));

    // accumlates for each level
    $this->assertTrue($this->Level->rate(2, 1));
    $this->assertEquals(2, $this->Level->field('rating'));
  }

  public function testLevelgenRequirement() {
    $this->Level->create();

    $noLineNoLevelgen = array(
      'name' => 'level',
      'content' => 'LevelName foo',
      'levelgen' => '',
      'description' => 'descriptive'
    );

    $noLineYesLevelgen = array(
      'name' => 'level',
      'content' => 'LevelName foo',
      'levelgen' => 'test',
      'description' => 'descriptive'
    );

    $yesLineNoLevelgen = array(
      'name' => 'level',
      'content' => "LevelName foo\r\nScript test.levelgen",
      'levelgen' => '',
      'description' => 'descriptive'
    );

    $yesLineYesLevelgen = array(
      'name' => 'level',
      'content' => "LevelName foo\nScript test.levelgen",
      'levelgen' => 'foo',
      'description' => 'descriptive'
    );

    $this->assertFalse($this->Level->save($noLineYesLevelgen));
    $this->assertTrue(!!$this->Level->save($noLineNoLevelgen));
    $this->assertFalse($this->Level->save($yesLineNoLevelgen));
    $this->assertTrue(!!$this->Level->save($yesLineYesLevelgen));
  }

  function testTrim() {
    $data = array(
      'content' => '  LevelName test   ',
      'description' => " \ntest\n ",
      'levelgen' => " \n\n ",
      'id' => 1
    );

    $expected = array(
      'name' => "test",
      'content' => "LevelName test",
      'description' => "test",
      'levelgen' => "",
      'id' => 1
    );
    
    $this->Level->create();
    $result = $this->Level->save($data);

    $this->assertTrue(!!$result);
    $this->assertEquals($expected, array_intersect($result['Level'], $expected));
  }

  public function testName() {
    $result = $this->Level->save(array(
      'content' => "LevelName Bob's level",
    ));
    $this->assertEquals('Bob\'s level', $result['Level']['name']);
  }

  public function testLevelFileName() {
    $result = $this->Level->save(array(
      'content' => "LevelName Bob's level",
    ));
    $this->assertEqual($result['Level']['level_filename'], 'bobs_level.level');
  }

  public function testLevelgenFileNameWithoutSuffix() {
    $result = $this->Level->save(array(
      'content' => "LevelName levelgen level\nScript test",
      'levelgen' => "blah"
    ));
    $this->assertEqual($result['Level']['levelgen_filename'], 'test.levelgen');
  }

  public function testLevelgenFileNameWithSuffix() {
    $result = $this->Level->save(array(
      'content' => "LevelName levelgen level II\nScript test.levelgen",
      'levelgen' => "blah"
    ));
    $this->assertEqual($result['Level']['levelgen_filename'], 'test.levelgen');
  }

  public function testMinimumLevelData() {
    /*
     * Only the "content" field is necessary. It is not validated as "required" 
     * in order to prevent client-side javaScript enforcement when submitting a 
     * level file directly.
     */
    $result = $this->Level->save(array(
      'content' => 'LevelName Minimal Level'
    ));

    $this->assertTrue(!!$result);
  }
}
?>
