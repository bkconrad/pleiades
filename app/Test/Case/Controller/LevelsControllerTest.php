<?php
App::uses('Level', 'Model');
class LevelsControllerTest extends ControllerTestCase {
  public $fixtures = array('app.level', 'app.user');
  public function setUp() {
    $this->Level = ClassRegistry::init('Level');
  }
  public function testIndex() {
    $result = $this->testAction('/levels/index/', array('return' => 'vars'));
    $this->assertEqual($result['levels'], $this->Level->find('all'));
    $this->assertGreaterThan(0, sizeof($result['levels']));
  }
}
