<?php
App::uses('Level', 'Model');
class LevelsControllerTest extends ControllerTestCase {
  public $fixtures = array('app.level', 'app.user');
  public $autoRender = false;
  public function setUp() {
    parent::setUp();
    $this->Level = ClassRegistry::init('Level');
  }

  public function testIndex() {
    $result = $this->testAction('/levels/index/', array('return' => 'vars'));
    $this->assertEqual($result['levels'], $this->Level->find('all'));
    $this->assertGreaterThan(0, sizeof($result['levels']));
  }

  public function testEditNoId() {
    $this->setExpectedException('BadRequestException');
    $this->testAction('/levels/edit/', array('return' => 'vars'));
  }

  public function testEditBadId() {
    $this->setExpectedException('BadRequestException');
    $this->testAction('/levels/edit/-1', array('return' => 'vars'));
  }

  public function testEditNotLoggedIn() {
    $this->setExpectedException('ForbiddenException');
    $this->testAction('/levels/edit/1', array('return' => 'vars'));
  }

  public function testEditUnownedLevel() {
    // mock Auth component
    $Levels = $this->generate('Levels', array(
        'components' => array('Auth' => array('user', 'loggedIn'))
      )
    );

    $Levels->Auth
      ->staticExpects($this->any())
      ->method('user')
      ->will($this->returnValue(2));

    $Levels->Auth
      ->expects($this->any())
      ->method('loggedIn')
      ->will($this->returnValue(true));

    $level = $this->Level->findByUserId(1);

    $this->setExpectedException('ForbiddenException');
    $this->testAction('/levels/edit/' . $level['Level']['id']);
  }
}
