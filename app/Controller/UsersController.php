<?php
class UsersController extends AppController{
	function login() {
    $this->Auth->login();
		if ($this->request->is('post')) {
			if($this->Auth->loggedIn()){
        Auth::user('groups', $this->User->getGroups());
				return $this->redirect($this->Auth->redirect());
			}else{
				$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
			}
		}
	}
	
	function logout(){
		$this->redirect($this->Auth->logout());
	}
}
