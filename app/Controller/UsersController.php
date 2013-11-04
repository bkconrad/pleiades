<?php
class UsersController extends AppController{
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny();
        $this->Auth->allow('view');
    }

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

    public function view($id) {
        $result = $this->User->findByUserId($id);
        $this->set('data', $result);
    }
}
