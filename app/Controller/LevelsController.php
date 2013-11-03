<?php
App::uses('AppController', 'Controller');

function array_flatten($arr) {
    $arr = array_values($arr);
    while (list($k,$v)=each($arr)) {
        if (is_array($v)) {
            array_splice($arr,$k,1,$v);
            next($arr);
        }
    }
    return $arr;
}

class LevelsController extends AppController {
    public $components = array('Search.Prg');
    public $presetVars = true;

    public $helpers = array('Html', 'Form');
    public $uses = array('Level', 'Rating', 'Comment');

    public $paginate = array(
            'limit' => 25,
            'order' => array(
                    'Level.name' => 'asc'
            )
    );

    function _checkFile($field) {
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

    // gets a level by id and returns appropriate errors
    function _getLevel($id) {
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

    /**
     * Attempts to read the uploaded screenshot (as a PNG image), then resizes it and
     * creates a thumbnail as needed.
     */
    function _getScreenshot() {
        if ($this->_isValidUpload('screenshot')) {
            $arr = $this->request->data['Level']['screenshot'];
            $parts = pathinfo($arr['name']);
            $newFileName = time() . '.' . $parts['extension'];
            $newPath = APP . 'webroot' . DS . 'img' . DS . $newFileName;
            $newThumbnailPath = APP . 'webroot' . DS . 'img' . DS . 't' .  $newFileName;

            $source = imagecreatefrompng($arr['tmp_name']);
            if(!is_resource($source)) {
                return false;
            }
            $sourceWidth = imagesx($source);
            $sourceHeight = imagesy($source);

            // resize image
            $resizeRatio = max(1, $sourceWidth / 800, $sourceHeight / 600);
            $destWidth = $sourceWidth / $resizeRatio;
            $destHeight = $sourceHeight / $resizeRatio;

            $dest = imagecreatetruecolor($destWidth, $destHeight);
            imagecopyresampled(
                    $dest, $source,
                    0, 0,
                    0, 0,
                    $destWidth, $destHeight,
                    $sourceWidth, $sourceHeight
            );

            imagepng($dest, $newPath);
            imagedestroy($dest);

            // create thumbnail
            $resizeRatio = max(1, $sourceWidth / 200, $sourceHeight / 150);
            $thumbWidth = $sourceWidth / $resizeRatio;
            $thumbHeight = $sourceHeight / $resizeRatio;

            $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);
            imagecopyresampled(
                    $thumb, $source,
                    0, 0,
                    0, 0,
                    $thumbWidth, $thumbHeight,
                    $sourceWidth, $sourceHeight
            );

            imagepng($thumb, $newThumbnailPath);
            imagedestroy($thumb);
            imagedestroy($source);
            $this->request->data['Level']['screenshot_filename'] = $newFileName;
            return $newFileName;
        }
    }

    /**
     * Checks that the file uploaded as Level.$field exists and
     * is actually an uploaded file.
     */
    function _isValidUpload($field) {
        $arr = $this->request->data['Level'][$field];
        return is_array($arr)
               && !$arr['error']
               && !empty($arr['tmp_name'])
               && $arr['tmp_name'] != 'none';
               // && is_uploaded_file($arr['tmp_name']);
    }

    function _performUpload() {
        if(!$this->Auth->loggedIn()) {
            if(!$this->Auth->login()) {
                throw new ForbiddenException('You must be logged in');
            }
        }

        if(isset($this->request->data['Level']['screenshot'])) {
            $this->_getScreenshot();
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
                $level = $this->_getLevel($id);
            } catch (Exception $e) {
            }
        }

        if($level) {
            if($level['Level']['user_id'] != $this->Auth->user('user_id')) {
                throw new ForbiddenException('You can only update a level you uploaded');
            }
            $this->request->data['Level']['id'] = $level['Level']['id'];
        } else {
            $this->Level->create();
        }

        $this->request->data['Level']['user_id'] = $this->Auth->user('user_id');
        return $this->Level->save($this->request->data);
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny();
        $this->Auth->allow('upload', 'download', 'raw', 'index', 'view', 'add', 'search', 'rate');

        if($this->Auth->loggedIn()) {
            $this->Auth->allow('edit');
        }
    }

    public function index() {
        $data = Cache::read('index_lists');
        if(!$data)
        {
            $fields = array(
                    'Level.id',
                    'Level.name',
                    'Level.rating',
                    'Level.game_type',
                    'Level.screenshot_filename',
                    'Level.user_id',
                    'Level.downloads',
                    'Level.team_count',
                    'User.username'
            );

            $data = array(
                    'Recently Updated' => $this->Level->find('all', array(
                            'fields' => $fields,
                            'order' => 'Level.last_updated DESC',
                            'recursive' => 1,
                            'limit' => 8
                    )),
                    'Highest Rated' => $this->Level->find('all', array(
                            'fields' => $fields,
                            'order' => 'Level.last_updated DESC',
                            'order' => 'Level.rating DESC',
                            'recursive' => 1,
                            'limit' => 8
                    )),
                    'Most Downloaded' => $this->Level->find('all', array(
                            'fields' => $fields,
                            'order' => 'Level.last_updated DESC',
                            'order' => 'Level.downloads DESC',
                            'recursive' => 1,
                            'limit' => 8
                    )),
                    'Random' => $this->Level->find('all', array(
                            'fields' => $fields,
                            'order' => 'Level.last_updated DESC',
                            'order' => 'RAND()',
                            'recursive' => 1,
                            'limit' => 8
                    )),
            );
            Cache::write('index_lists', $data);
        }

        $this->set('levelLists', $data);
    }

    public function all() {
        $this->set('levels', $this->paginate());
    }

    public function edit($id = null) {
        $level = $this->_getLevel($id);

        if(!$this->Auth->loggedIn()) {
            throw new ForbiddenException('You must be logged in to edit a level');
        }

        if(!$this->isAdmin() && $level['Level']['user_id'] != $this->Auth->user('user_id')) {
            throw new ForbiddenException('You can only edit a level you uploaded');
        }

        if($this->request->is('post') || $this->request->is('put')) {

            $this->Level->id = $level['Level']['id'];
            $this->Level->set('user_id', $this->Auth->user('user_id'));
            $this->Level->set('author', $this->Auth->user('username'));

            if(empty($this->data['Level']['author']) || !$this->isAdmin()) {
                $userid = $level['Level']['user_id'];
                $user = $this->Level->User->findByUserId($userid);
                $this->Level->set('author', $user['User']['username']);
                $this->Level->set('user_id', $userid);
            }

            $this->_checkFile('content');
            $this->_checkFile('levelgen');

            if(!empty($this->request->data['Level']['screenshot']['tmp_name'])) {
                if(!$this->_getScreenshot()) {
                    throw new BadRequestException('Unable to read screenshot file. You must upload a .png image');
                }
            }

            $result = $this->Level->save($this->request->data);
            if($result) {
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
        $data = Cache::read("level_view_$id");
        if(!$data)
        {
            $level = $this->Level->findById($id);
            $current_user_rating = $this->Rating->findByUserIdAndLevelId($this->Auth->user('user_id'), $id);
            $comments = $this->Comment->findAllByLevelId($id);
            $data = compact('level', 'current_user_rating', 'comments');
            Cache::write("level_view_$id", $data);
        }

        $this->set('logged_in', $this->Auth->loggedIn());
        $this->set('is_owner', intval($data['level']['Level']['user_id']) == intval($this->Auth->user('user_id')));
        $this->set($data);
    }

    public function rate($id, $value) {
        if(!$this->Auth->loggedIn() && !$this->Auth->login()) {
            throw new ForbiddenException('You must be logged in');
        }

        $this->Level->id = $id;
        if($this->Level->rate($this->Auth->user('user_id'), $value)) {
            if($this->layout == 'client') {
                $level = $this->Level->findById($id);
                $this->Session->setFlash('New rating for ' . $level['Level']['name'] . ' by ' . $level['Level']['author'] . ': ' . $level['Level']['rating']);
                return;
            }
        } else {
            $this->Session->setFlash(array_shift($this->Level->validationErrors));
            throw new BadRequestException($this->Level->validationErrors);
        }
        return $this->redirect($this->referer());
    }

    public function add() {
        if($this->request->is('post')) {
            $this->Level->create();
            $this->Level->set('user_id', $this->Auth->user('user_id'));

            $this->_checkFile('content');
            $this->_checkFile('levelgen');

            if(isset($this->request->data['Level']['screenshot'])) {
                $this->_getScreenshot();
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

    public function raw($id = null, $type = 'content', $uncounted = false) {
        $level = $this->_getLevel($id);

        if($type !== 'content' && $type !== 'levelgen') {
            throw new BadRequestException('Valid display modes are "level" and "levelgen"');
        }

        $responseBody = $level['Level'][$type];
        if($type == 'levelgen' && !empty($level['Level']['levelgen_filename'])) {
            $responseBody = "-- " . $level['Level']['levelgen_filename'] . "\r\n" . $responseBody;
        }

        if($type != 'levelgen' && !$uncounted) {
            $this->Level->id = $level['Level']['id'];
            $this->Level->saveField('downloads', $level['Level']['downloads'] + 1);
        }

        $this->response->type('text/text');
        $this->response->body($responseBody);
        return $this->response;
    }

    public function download($id = null) {
        $level = $this->_getLevel($id);
        $this->Level->id = $level['Level']['id'];
        $this->Level->saveField('downloads', $level['Level']['downloads'] + 1);

        $levelName = $level['Level']['level_filename'];

        $tmp = tempnam('/tmp', 'levelzip_');
        $zip = new ZipArchive();
        $zip->open($tmp, ZIPARCHIVE::OVERWRITE);
        $zip->addFromString($levelName, $level['Level']['content']);

        if(!empty($level['Level']['levelgen'])) {
            $zip->addFromString($level['Level']['levelgen_filename'], $level['Level']['levelgen']);
        }

        $zip->close();

        $filename = preg_replace('/\.level$/', '', $levelName) . '.zip';
        $this->response->file($tmp, array('download' => true, 'name' => $filename));
        return $this->response;
    }

    // client unified write interface: updates or creates as needed
    public function upload() {
        if(!$this->_performUpload()) {
            $this->response->statusCode(403);
            $this->response->body(array_shift($this->Level->validationErrors));
            return $this->response;
        }

        $this->response->body($this->Level->getId());
        return $this->response;
    }

    public function delete($id) {
        $level = $this->_getLevel($id);

        if(!$this->Auth->loggedIn() || intval($level['Level']['user_id']) != intval($this->Auth->user('user_id')) && !$this->isAdmin()) {
            throw new ForbiddenException('You can only delete a level you uploaded');
        }

        $this->Level->delete($id);
        $this->redirect('index');
    }

    public function search() {
        $this->Prg->commonProcess();
        $this->paginate['conditions'] = $this->Level->parseCriteria($this->passedArgs);
        $this->set('levels', $this->paginate());
        $tags = $this->Level->Tag->find('list');
        $this->set(compact('tags'));
    }

    public function massupload() {
        if($this->request->is('post')) {
            if(!$this->_isValidUpload('zipFile')) {
                throw new BadRequestException('You must upload a .zip file');
            }

            $filename = $this->request->data['Level']['zipFile']['tmp_name'];

            $zip = new ZipArchive();
            if(!$zip->open($filename)) {
                throw new BadRequestException('Invalid .zip file');
            }

            $result = array();

            $this->request->data = array();
            for($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->statIndex($i);
                $entryFilename = $entry['name'];
                $entryContents = $zip->getFromIndex($i);

                if(strstr($entryFilename, '__MACOSX')) {
                    continue;
                }

                // information about the upload
                $info = array(
                        'errors' => array(),
                        'warnings' => array(),
                        'name' => null,
                        'id' => null,
                        'filename' => $entryFilename
                );

                if(preg_match('/\.level$/', $entryFilename)) {
                    $this->request->data['Level'] = array();
                    $this->request->data['Level']['content'] = $entryContents;

                    // find levelgen if needed
                    $matches = array();
                    if(preg_match('/Script +([^ \n]+)/', $entryContents, $matches) && sizeof($matches) > 1 && !empty($matches[1])) {
                        $dir = dirname($entry['name']);
                        $levelgenFilename = trim(preg_replace('/\.levelgen$/', '', $matches[1]));
                        $levelgenContents = $zip->getFromName($levelgenFilename . '.levelgen');
                        $levelgenContents = $levelgenContents !== false ? $levelgenContents : $zip->getFromName($levelgenFilename);
                        $this->request->data['Level']['levelgen'] = $levelgenContents;
                        if($levelgenContents === false) {
                            array_push($info['errors'], "Could not find specified levelgen file '$levelgenFilename' in archive");
                        }
                    }

                    $this->Level->validationErrors = array();
                    $level = $this->_performUpload();
                    if(!$level) {
                        $info['errors'] = array_merge($info['errors'], array_flatten($this->Level->validationErrors));
                        array_push($result, $info);
                        continue;
                    }

                    $info['name'] = $level['Level']['name'];
                    $info['id'] = $level['Level']['id'];
                    array_push($result, $info);
                }
            }

            $zip->close();

            $this->set('uploads', $result);
        }
    }
}
?>
