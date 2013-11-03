<?php
App::uses('CommentsController', 'Controller');

/**
 * CommentsController Test Case
 *
 */
class CommentsControllerTest extends ControllerTestCase {

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

    // configures a mock as fixture user 'bob'
    function mockAsBob() {
        // mock Auth component
        $options = array(
                'components' => array('Auth' => array('user', 'loggedIn', 'login'))
        );

        $Comments = $this->generate('Comments', $options);

        $Comments->Auth
        ->staticExpects($this->any())
        ->method('user')
        ->will($this->returnValue(2));

        $Comments->Auth
        ->expects($this->any())
        ->method('loggedIn')
        ->will($this->returnValue(true));

        $Comments->Auth
        ->expects($this->any())
        ->method('login')
        ->will($this->returnValue(true));

        return $Comments;
    }

    // configures a mock as an unauthenticated user
    function mockAsUnauthenticated() {
        $Comments = $this->generate('Comments', array(
                'components' => array(
                        'Auth' => array(
                                'loggedIn'
                        )
                )
        ));

        $Comments->Auth
        ->expects($this->any())
        ->method('loggedIn')
        ->will($this->returnValue(false));

        return $Comments;
    }

    public function testAdd() {
        $this->mockAsBob();

        $comment = array(
                'Comment' => array(
                        'level_id' => 1
                )
        );

        $this->testAction('/comments/add', array('data' => $comment));
    }

    public function testAddNotLoggedIn() {
        $this->mockAsUnauthenticated();

        $comment = array(
                'Comment' => array(
                )
        );

        $this->setExpectedException('BadRequestException');
        $this->testAction('/comments/add', array('data' => $comment));
    }

}
