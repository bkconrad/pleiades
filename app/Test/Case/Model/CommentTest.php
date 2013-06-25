<?php
App::uses('Comment', 'Model');

/**
 * Comment Test Case
 *
 */
class CommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.comment',
		'app.user',
		'app.level',
		'app.tag',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Comment = ClassRegistry::init('Comment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Comment);

		parent::tearDown();
	}

/**
 * testLevelExists method
 *
 * @return void
 */
	public function testLevelExists() {
        $this->Comment->create();
        $this->assertFalse($this->Comment->levelExists());
        $this->assertFalse($this->Comment->save());

        $this->Comment->set('level_id', '1337');
        $this->assertFalse($this->Comment->levelExists());
        $this->assertFalse($this->Comment->save());

        $this->Comment->set('level_id', '');
        $this->assertFalse($this->Comment->levelExists());
        $this->assertFalse($this->Comment->save());

        $level = $this->Comment->Level->find('first');
        $this->Comment->set('level_id', $level['Level']['id']);
        $this->assertTrue($this->Comment->levelExists());
        $this->assertTrue(!!$this->Comment->save());
	}

    public function testUserExists() {
        $this->Comment->create();
        $this->assertFalse($this->Comment->userExists());
        $this->assertFalse($this->Comment->save());

        $this->Comment->set('user_id', '1337');
        $this->assertFalse($this->Comment->levelExists());
        $this->assertFalse($this->Comment->save());

        $this->Comment->set('user_id', '');
        $this->assertFalse($this->Comment->userExists());
        $this->assertFalse($this->Comment->save());

        $user = $this->Comment->User->find('first');
        $this->Comment->set('user_id', $user['User']['user_id']);
        $this->assertTrue($this->Comment->userExists());
        $this->assertTrue(!!$this->Comment->save());
    }

}
