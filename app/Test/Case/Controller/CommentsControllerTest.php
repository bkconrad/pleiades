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
            'app.levels_tag'
    );

    public function setUp() {
        parent::setUp();
        $this->Comment = ClassRegistry::init('Comment');
        $this->User = ClassRegistry::init('User');
        Cache::clear();
    }

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

    function mockAsAlice() {
        // mock Auth component
        $options = array(
                'methods' => array('isAdmin'),
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

        $Comments
        ->expects($this->any())
        ->method('isAdmin')
        ->will($this->returnValue(true));

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

    public function testDeleteOwnComment() {
        $Comments = $this->mockAsBob();
        $this->Comment->create(array('user_id' => $Comments->Auth->user('id'), 'level_id' => 1));
        $id = $this->Comment->save()['Comment']['id'];
        $this->testAction("/comments/delete/$id");
        $this->assertEmpty($this->Comment->findById($id));
    }

    public function testDeleteOthersCommentOnOwnLevel() {
        $Comments = $this->mockAsBob();
        $this->Comment->create(array('user_id' => 1, 'level_id' => 2));
        $id = $this->Comment->save()['Comment']['id'];
        $this->testAction("/comments/delete/$id");
        $this->assertEmpty($this->Comment->findById($id));
    }

    public function testDeleteOthersCommentOnOthersLevel() {
        $Comments = $this->mockAsBob();
        $this->Comment->create(array('user_id' => 1, 'level_id' => 1));
        $id = $this->Comment->save()['Comment']['id'];
        $this->setExpectedException('ForbiddenException');
        $this->testAction("/comments/delete/$id");
    }

    public function testDeleteOthersCommentOnOthersLevelAdmin() {
        $Comments = $this->mockAsAlice();
        $this->Comment->create(array('user_id' => 1, 'level_id' => 1));
        $id = $this->Comment->save()['Comment']['id'];
        $this->testAction("/comments/delete/$id");
        $this->assertEmpty($this->Comment->findById($id));
    }

}
