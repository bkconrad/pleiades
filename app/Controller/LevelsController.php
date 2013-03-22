<?php
class LevelsController extends AppController {
  public $helpers = array('Html', 'Form');
  public $components = array('Auth');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('index', 'view', 'add');
    if($this->Auth->loggedIn()) {
      $this->Auth->allow('rate');
    }
  }

  public function index() {
    $this->set('levels', $this->Level->find('all'));
  }

  public function edit($id) {
    $level = $this->Level->findById($id);
    if(empty($level)) {
      $this->flash("Level not found", $this->referer());
    }

    if(!$this->Auth->loggedIn()) {
      $this->flash("You must be logged in to edit a level", $this->referer());
    }

    if($level["User"]["user_id"] != $this->Auth->user('user_id')) {
      $this->flash("You can only edit a level you uploaded", $this->referer());
    }

    if($this->request->is('post') || $this->request->is('put')) {
      $this->Level->id = $id;
      if($this->Level->save($this->request->data)) {
        $this->Session->setFlash('Level updated');
        $this->redirect(array('action' => 'edit', $id));
      } else {
        $this->Session->setFlash('Unable to update level');
      }
    }

    if(empty($this->request->data)) {
      $this->request->data = $level;
    }
  }

  public function view($id) {
    $level = $this->Level->findById($id);
    $this->set('level', $level);
    $this->set('logged_in', $this->Auth->loggedIn());
    $this->set('is_owner', $level['User']['user_id'] == $this->Auth->user('user_id'));
  }

  public function rate($id, $value) {
    $this->Level->id = $id;
    if($this->Level->rate($this->Auth->user('user_id'), $value)) {
      $this->Session->setFlash('Rating updated');
    } else {
      $this->Session->setFlash('Could not set rating');
    }
    $this->redirect($this->referer());
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
