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

}
