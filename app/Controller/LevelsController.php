<?php
class LevelsController extends AppController {
  public $helpers = array('Html', 'Form');
  public $components = array('Auth');
  public $uses = array('Level', 'Rating');

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

  public function edit($id = null) {
    if($id == null) {
      throw new BadRequestException('You must specify a level');
    }

    $level = $this->Level->findById($id);
    if(empty($level)) {
      throw new BadRequestException('Level not found');
    }

    if(!$this->Auth->loggedIn()) {
      throw new ForbiddenException('You must be logged in to edit a level');
    }

    if($level["User"]["user_id"] != $this->Auth->user('user_id')) {
      throw new ForbiddenException('You can only edit a level you uploaded');
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
    $this->set('current_rating', $this->Rating->findByUserIdAndLevelId($this->Auth->user('user_id'), $id));
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
    }
  }

  public function raw($id = null, $type = 'content') {
    if($id == null) {
      throw new BadRequestException('Please specify a level');
    }
    $level = $this->Level->findById($id);
    if(empty($level)) {
      throw new NotFoundException('Level not found');
    }

    if($type !== 'content' && $type !== 'levelgen') {
      throw new BadRequestException('Valid display modes are "level" and "levelgen"');
    }

    $this->response->type('text/text');
    $this->response->body($level['Level'][$type]);
    return $this->response;
  }

  public function download($id = null) {
    if($id == null) {
      throw new BadRequestException('Please specify a level');
    }
    $level = $this->Level->findById($id);
    if(empty($level)) {
      throw new NotFoundException('Level not found');
    }
    $this->response->body($level['Level']['content']);

    $levelName = $level['User']['username'] . "_" . $level['Level']['name'] . ".level";
    $levelName = strtr($levelName, array(
      ' ' => '_',
      '-' => '_'
    ));
    $levelName = ereg_replace('[^a-zA-Z0-9_.]', '', $levelName);

    $this->response->download($levelName);
    return $this->response;
  }
}
?>
