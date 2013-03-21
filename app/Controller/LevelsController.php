<?php
class LevelsController extends AppController {
  public $helpers = array('Html', 'Form');
  public $components = array('Auth');

  public function index() {
    $this->set('levels', $this->Level->find('all'));
  }

  public function view($id) {
    $this->set('level', $this->Level->findById($id));
  }

  public function add() {
    $this->set('user', $this->Auth->user('username'));
    if($this->request->is('post')) {
      $this->Level->create();
      if($this->Level->save($this->request->data)) {
        $this->Session->setFlash('Your post has been saved.');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash('could not save level');
      }
    }
  }
}
?>
