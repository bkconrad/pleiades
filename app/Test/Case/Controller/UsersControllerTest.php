<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 *
 */
class UsersControllerTest extends ControllerTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = array(
            'app.user',
            'app.level',
            'app.tag',
    );

    public function testView() {
        // view bob's profile
        $result = $this->testAction('/users/view/2', array('return' => 'view'));

        // should include all of bob's levels, including this one
        $this->assertContains("another level by bob", $result);
    }

    public function testViewNonExistantUser() {
        $this->setExpectedException('NotFoundException');
        $this->testAction('/users/view/1000');
    }
}
