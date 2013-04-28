<?php
App::import('Model', 'Level');
App::import('Model', 'Rating');
class LevelTest extends CakeTestCase {
  public $fixtures = array('app.level', 'app.user', 'app.rating', 'app.user_group');
  public function setUp() {
    parent::setUp();
    $this->Rating = ClassRegistry::init('Rating');

    // need to change the db config for User (since it is manually set to 
    // 'forum' in the model definition)
    $this->Level = ClassRegistry::init('Level');
    $this->Level->User->useDbConfig = 'test';

    // then we need to set the "admin" group number for phpbb
    Configure::write('Phpbb.admin_group', 2);
  }

  public function testRating() {
    $this->Level->id = 2;

    // can be negative
    $this->assertTrue($this->Level->rate(1, -1));
    $this->assertEquals(-1, $this->Level->field('rating'));

    // replaces old ratings
    $this->assertTrue($this->Level->rate(1, 1));
    $this->assertEquals(1, $this->Level->field('rating'));

    // accumlates for each level
    $this->assertTrue($this->Level->rate(3, 1));
    $this->assertEquals(2, $this->Level->field('rating'));
  }

  public function testRatingOwnLevel() {
    // alice's level
    $this->Level->id = 1;
    $this->assertFalse($this->Level->rate(1, 1));
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

  public function testLineBreak() {
    $data = array(
      "content" => "  LevelName test\r\nScript foo\nblah\nunix",
      "levelgen" => "test\nsome\nstuff",
    );

    $expected = array(
      "content" => "LevelName test\r\nScript foo\r\nblah\r\nunix",
      "levelgen" => "test\r\nsome\r\nstuff",
    );

    $result = $this->Level->save($data);
    $result = array_intersect_key($result['Level'], $expected);
    $this->assertEquals($expected, $result);
  }

  public function testConsecutiveLineBreak() {
    $data = array(
      "content" => "  LevelName test again\r\n\r\nScript foo\nblah\nunix",
      "levelgen" => "test\r\n\nsome\nstuff",
    );

    $expected = array(
      "content" => "LevelName test again\r\n\r\nScript foo\r\nblah\r\nunix",
      "levelgen" => "test\r\n\r\nsome\r\nstuff",
    );

    $result = $this->Level->save($data);
    $result = array_intersect_key($result['Level'], $expected);
    $this->assertEquals($expected, $result);
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

  public function testLevelDatabaseId() {
    $result = $this->Level->save(array(
      "content" => "LevelName DBID Test Level\nLevelDatabaseId 1"
    ));

    // do a query to trigger afterFind
    $result = $this->Level->findById($result['Level']['id']);

    // the supplied id should be ignored and removed 
    // then the new id line should be appended
    $this->assertNotRegExp('/LevelDatabaseId\s+1/', $result['Level']['content']);
    $this->assertRegExp('/LevelDatabaseId\s+' . $result['Level']['id'] . '/', $result['Level']['content']);
  }

  public function testUpdated() {
    $result = $this->Level->findById(3);
    $oldTime = $result['Level']['last_updated'];
    $result = $this->Level->save(array(
      "content" => "LevelName The Newly Updated Time Test Level",
      "id" => $result['Level']['id']
    ));

    sleep(1);

    // editing contents or levelgens should update
    $result = $this->Level->findById(3);
    $newTime = $result['Level']['last_updated'];
    $this->assertNotEquals($oldTime, $newTime);


    // but ratings etc. should not
    $this->Level->rate(1, 1);
    $result = $this->Level->findById($result['Level']['id']);
    $ratingTime = $result['Level']['last_updated'];
    $this->assertEquals($newTime, $ratingTime);
  }

  public function testSetAuthor() {
    $Auth = $this->getMock('Auth');
    $Auth
      ->staticExpects($this->any())
      ->method('user')
      ->with('user_id')
      ->will($this->returnValue(2));

    $result = $this->Level->save(array(
      "content" => "LevelName Author Test",
      "author" => "nobody",
      'user_id' => 2
    ));

    $this->assertEquals('nobody_author_test.level', $result['Level']['level_filename']);
    $this->assertEquals('nobody', $result['Level']['author']);
  }

  public function testAuthorAsUsername() {
    $Auth = $this->getMock('Auth');
    $Auth
      ->staticExpects($this->any())
      ->method('user')
      ->with('user_id')
      ->will($this->returnValue(2));

    $result = $this->Level->save(array('Level' => array(
      "content" => "LevelName Author As Username Test",
      "author" => "nobody",
      "user_id" => "2"
    )));

    $found = $this->Level->findById($result['Level']['id']);
    $this->assertEquals('nobody', $found['User']['username']);
  }

  public function testGameType() {
    $this->Level->create();
    $this->Level->save(array(
      'content' => "LevelName ctf level\nCTFGameType"
    ));
    $this->assertEquals('CTF', $this->Level->field('game_type'));

    $result = $this->Level->save(array(
      'content' => "LevelName bitmatch level\nGameType"
    ));
    $this->assertEquals('Bitmatch', $this->Level->field('game_type'));

    $this->Level->save(array(
      'content' => "LevelName hunters level\nHuntersGameType"
    ));
    $this->assertEquals('Nexus', $this->Level->field('game_type'));
  }

  public function testTeamCount() {
    $this->Level->create();
    $this->Level->save(array(
      'content' => "LevelName ctf level\nTeam\nTeam"
    ));
    $this->assertEquals(2, $this->Level->field('team_count'));
  }
}
?>
