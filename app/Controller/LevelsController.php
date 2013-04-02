<?php
App::uses('AppController', 'Controller');
class LevelsController extends AppController {
  public $helpers = array('Html', 'Form');
  public $uses = array('Level', 'Rating');

  public $paginate = array(
    'limit' => 25,
    'order' => array(
      'Level.name' => 'asc'
    )
  );

  // gets a level by id and returns appropriate errors
  function getLevel($id) {
    if($id == null) {
      throw new BadRequestException('You must specify a level');
    }

    if(!is_numeric($id)) {
      $level = $this->Level->findByLevelFilename($id . '.level');
    } else {
      $level = $this->Level->findById($id);
    }

    if(empty($level)) {
      throw new BadRequestException('Level not found');
    }

    return $level;
  }

  function getScreenshot($arr) {
    if ((isset($arr['error']) && $arr['error'] == 0) ||
      (!empty( $arr['tmp_name']) && $arr['tmp_name'] != 'none')
    ) {
      $parts = pathinfo($arr['name']);
      $newFileName = time() . '.' . $parts['extension'];
      $newPath = APP . 'webroot' . DS . 'img' . DS . $newFileName;
      move_uploaded_file($arr['tmp_name'], $newPath);
      $this->request->data['Level']['screenshot_filename'] = $newFileName;
    }
  }

  public function checkFile($field) {
    if(!isset($this->request->data['Level'][$field.'File'])) {
      return false;
    }

    $arr = $this->request->data['Level'][$field.'File'];

    if ((isset($arr['error']) && $arr['error'] == 0) ||
      (!empty( $arr['tmp_name']) && $arr['tmp_name'] != 'none')
    ) {
      if(is_uploaded_file($arr['tmp_name'])) {
        $handle = fopen($arr['tmp_name'], 'r');
        $content = fread($handle, $arr['size']);
        fclose($handle);
        $this->request->data['Level'][$field] = $content;
        return true;
      }
    }
    return false;
  }

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow('upload', 'download', 'raw', 'index', 'view', 'add');

    if($this->Auth->loggedIn()) {
      $this->Auth->allow('rate', 'edit');
    }
  }

  public function index() {
    $this->set('levels', $this->paginate('Level'));
  }

  public function edit($id = null) {
    $level = $this->getLevel($id);

    if(!$this->Auth->loggedIn()) {
      throw new ForbiddenException('You must be logged in to edit a level');
    }

    if($level['User']['user_id'] != $this->Auth->user('user_id')) {
      throw new ForbiddenException('You can only edit a level you uploaded');
    }

    if($this->request->is('post') || $this->request->is('put')) {
      $this->Level->id = $level['Level']['id'];
      $this->Level->set('user_id', $this->Auth->user('user_id'));

      $this->checkFile('content');
      $this->checkFile('levelgen');

      if(isset($this->request->data['Level']['screenshot'])) {
        $this->getScreenshot($this->request->data['Level']['screenshot']);
      }

      if($this->Level->save($this->request->data)) {
        $this->Session->setFlash('Level updated');
        return $this->redirect(array('action' => 'view', $id));
      } else {
        $this->Session->setFlash('Could not save level: ' . array_shift($this->Level->validationErrors));
      }
    }

    if(empty($this->request->data)) {
      $this->request->data = $level;
    }
    $tags = $this->Level->Tag->find('list');
    $this->set(compact('tags'));
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
    return $this->redirect($this->referer());
  }

  public function add() {
    if($this->request->is('post')) {
      $this->Level->create();
      $this->Level->set('user_id', $this->Auth->user('user_id'));

      $this->checkFile('content');
      $this->checkFile('levelgen');

      if(isset($this->request->data['Level']['screenshot'])) {
        $this->getScreenshot($this->request->data['Level']['screenshot']);
      }

      if($this->Level->save($this->request->data)) {
        $this->Session->setFlash('Your post has been saved.');
        return $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash('Could not save level: ' . array_shift($this->Level->validationErrors));
      }
    } else {
      $tags = $this->Level->Tag->find('list');
      $this->set(compact('tags'));
    }
  }

  public function raw($id = null, $type = 'content') {
    $level = $this->getLevel($id);

    if($type !== 'content' && $type !== 'levelgen') {
      throw new BadRequestException('Valid display modes are "level" and "levelgen"');
    }

    $responseBody = $level['Level'][$type];
    if($type == 'levelgen' && !empty($level['Level']['levelgen_filename'])) {
      $responseBody = "-- " . $level['Level']['levelgen_filename'] . "\r\n" . $responseBody;
    }

    $this->response->type('text/text');
    $this->response->body($responseBody);
    return $this->response;
  }

  public function download($id = null) {
    $level = $this->getLevel($id);

    $levelName = $level['Level']['level_filename'];
    $levelName = preg_replace('/\.level$/i', '', $levelName);

    $tmp = tempnam('/tmp', 'levelzip_');
    $zip = new ZipArchive();
    $zip->open($tmp, ZIPARCHIVE::OVERWRITE);
    $zip->addFromString($levelName, $level['Level']['content']);

    if(!empty($level['Level']['levelgen'])) {
      $zip->addFromString($level['Level']['levelgen_filename'], $level['Level']['levelgen']);
    }

    $zip->close();

    $this->response->file($tmp);
    $this->response->download($levelName . '.zip');
    return $this->response;
  }

  // client unified write interface: updates or creates as needed
  public function upload() {
    if(!$this->Auth->loggedIn()) {
      if(!$this->Auth->login()) {
        throw new ForbiddenException('You must be logged in');
      }
    }

    if(!isset($this->request->data['Level']['content'])) {
      throw new BadRequestException('No level content found');
    }

    $id = null;
    $matches = array();
    preg_match('/LevelDatabaseId\s+([^\r\n]+)/', $this->request->data['Level']['content'], $matches);

    if(count($matches) > 1) {
      $id = trim($matches[1]);
    }

    $level = false;
    if($id !== null) {
      try {
        $level = $this->getLevel($id);
      } catch (Exception $e) {
      }
    }

    if($level) {
      if($level['Level']['user_id'] != $this->Auth->user('user_id')) {
        throw new ForbiddenException('You can only update a level you uploaded');
      }
      $this->request->data['Level']['id'] = $level['Level']['id'];
    }

    $this->request->data['Level']['user_id'] = $this->Auth->user('user_id');

    if(!$this->Level->save($this->request->data)) {
      $this->response->statusCode(403);
      $this->response->body(array_shift($this->Level->validationErrors));
      return $this->response;
    }

    $this->response->body($this->Level->getId());
    return $this->response;
  }
}
?>
