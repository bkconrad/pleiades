<?php
App::uses('NotificationsController', 'Controller');

/**
 * NotificationsController Test Case
 *
 */
class NotificationsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.notification',
		'app.user',
		'app.level',
		'app.tag',
		'app.levels_tag'
	);

}
