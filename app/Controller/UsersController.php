<?php
class UsersController extends AppController{
    function login() {
        if($this->Auth->login()) {
            $this->User->id = $this->Auth->user('user_id');
            $is_admin = in_array(strval(Configure::read('Phpbb.admin_group')), $this->User->getGroups());
            $this->Session->write('isAdmin', $is_admin);
        }
        if ($this->request->is('post')) {
            if($this->Auth->loggedIn()){
                return $this->redirect($this->Auth->redirect());
            }else{
                $this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
            }
        }
    }

    function logout(){
        $this->redirect($this->Auth->logout());
        $this->Session->delete('isAdmin');
    }
}
