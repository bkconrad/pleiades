<?php
class AppController extends Controller{
	var $components = array(
		'Auth'=> array(
			'authenticate' => array(
				'Phpbb3' => array(
					'fields' => array('username' => 'username', 'password' => 'user_password')
				)
			)
		),
		'Session'
	);

  var $helpers = array(
    'Js',
    'Html'
  );

  public function beforeFilter() {
    $this->set('currentUserId', $this->Auth->user('user_id'));
    $this->set('currentUserName', $this->Auth->user('username'));
  }
}
