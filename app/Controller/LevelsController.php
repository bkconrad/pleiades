<?php
class LevelsController extends AppController {
  public $helpers = array('Html', 'Form');
  public $components = array('Auth');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'view', 'add');
  }

  public function index() {
    $this->set('levels', $this->Level->find('all'));
  }

  public function view($id) {
    $this->set('level', $this->Level->findById($id));
  }

  public function rate($id, $value) {
    if($this->Level->rate($id, $this->Auth->user('user_id'), $value)) {
      $this->Session->setFlash('Rating updated');
    } else {
      $this->Session->setFlash('Could not set rating');
    }
  }

  public function add() {
    if($this->request->is('post')) {
      $this->Level->create();
      $this->Level->set('user_id', $this->Auth->user('user_id'));
      if($this->Level->save($this->request->data)) {
        $this->Session->setFlash('Your post has been saved.');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash('could not save level');
      }
    } else {
    }
  }
}
?>
