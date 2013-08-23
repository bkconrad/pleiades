<?php
class AppController extends Controller{
	var $components = array(
		'Auth'=> array(
			'authenticate' => array(
				'Phpbb3' => array(
					'fields' => array('username' => 'username', 'password' => 'user_password'),
          'userModel' => 'User'
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
    if($this->request->header('User-Agent') == 'Bitfighter') {
      $this->layout = 'client';
    }

    $this->set('currentUserId', $this->Auth->user('user_id'));
    $this->set('currentUserName', $this->Auth->user('username'));
    $this->set('isAdmin', $this->Session->read('isAdmin'));
  }

  public function isAdmin() {
    return $this->Session->read('isAdmin');
  }
}
