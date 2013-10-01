<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
			'app.user', 'app.user_group'
	);

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->User->useDbConfig = 'test';
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

	public function testGetGroups() {
		$this->User->id = 2;
		$result = $this->User->getGroups();
		$this->assertEquals(array('1', '2'), $result);

		$this->User->id = 1;
		$result = $this->User->getGroups();
		$this->assertEquals(array('1'), $result);
	}
}
