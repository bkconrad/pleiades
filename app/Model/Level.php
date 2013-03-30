<?php
class Level extends AppModel {
  public $uses = array('User', 'Rating');
  public $belongsTo = array('User' => array('fields' => array('username', 'user_id')));
  public $hasAndBelongsToMany = array('Tag');
  public $validate = array(
    'content' => array(
      'levelgenExistence' => array(
        'allowEmpty' => true,
        'rule' => 'levelgenExistence',
        'message' => 'If your level specifies a script it must also include a levelgen, otherwise the levelgen must be empty',
      )
    )
  );

  /*
   * Converts $text into a file name with the given extension using the 
   * following rules:
   *  - anything which is not a letter, space, or underscore is removed
   *  - all letters are made lowercase
   *  - both spaces and underscores are removed from the beginning and end
   *  - groups of spaces or underscores with length > 0 are changed to underscores
   *  - the appropriate extension is appended
   */
  public static function stringToFileName($text, $extension = '.level') {
    $text = preg_replace('/[^a-zA-Z _]+/', '', $text);
    $text = preg_replace('/^[ _]+/', '', $text);
    $text = preg_replace('/[ _]+$/', '', $text);
    $text = strtolower($text);
    $text = preg_replace('/[ _]+/', '_', $text);
    $text .= $extension;
    return $text;
  }


  // checks that files have levelgens IFF the level has a script line and sets 
  // the levelgen filename as needed
  public function levelgenExistence($check) {
    $scripts = preg_grep('/^\s*Script\s.*$/', split("\n", $this->data['Level']['content']));
    $parts = preg_split('/\s+/', array_shift($scripts));

    // if the Script line with one or more arguments is found, require a levelgen
    if(array_key_exists(1, $parts) && trim($parts[1]) != '') {
      $fileName = $parts[1];
      $this->data['Level']['levelgen_filename'] = $fileName;
      $this->data['Level']['levelgen'] = trim($this->data['Level']['levelgen']);
      return !empty($this->data['Level']['levelgen']);
    } else {
      return empty($this->data['Level']['levelgen']);
    }
  }

  public function beforeValidate($option) {
    // content must have a LevelName line
    $match = array();
    preg_match('/\s*LevelName\s+([^\n]*)/', $this->data['Level']['content'], $match);
    if(count($match) < 2) {
      array_push($this->validationErrors, 'You must include a LevelName');
      return false;
    }

    $name = $match[1];
    $name = preg_replace('/"/', '', $name);
    $this->set('name', $name);

    $prefix = '';
    if(isset($this->data['Level']['user_id'])) {
      $user = $this->User->findByUserId($this->data['Level']['user_id']);
      $prefix = Level::stringToFileName($user['User']['username'], '') . '_';
    }
    $levelFilename = $prefix . Level::stringToFileName($name, '.level');
    $result = $this->findByLevelFilename($levelFilename);

    if($result) {
      array_push($this->validationErrors, 'The name "' . $this->data['Level']['name'] . '" is too similar to "' . $result['Level']['name'] . '" by ' . $result['User']['username'] . '. Please be more creative.');
      return false;
    }

    $this->set('level_filename', $levelFilename);

    foreach (array('levelgen', 'content', 'name', 'description') as $field) {
      if(isset($this->data['Level'][$field])) {
        $this->data['Level'][$field] = trim($this->data['Level'][$field]);
      }
    }
    return true;
  }

  public function rate($user_id, $value) {
    $Rating = ClassRegistry::init('Rating');
    $rating = $Rating->findByUserIdAndLevelId($user_id, $this->id);

    if(!$this->id) {
      return false;
    }

    if(empty($rating)) {
      // create a new rating
      $Rating->create();
      $Rating->set(array(
        'level_id' => $this->id,
        'user_id' => $user_id,
        'value' => $value
      ));
    } else {
      // change the current rating
      $Rating->set(array(
        'id' => $rating["Rating"]['id'],
        'value' => $value
      ));
    }

    if(!$Rating->save()) {
      return false;
    }

    // recalculate rating
    $ratings = $Rating->findAllByLevelId($this->id);
    $total = 0;
    foreach ($ratings as $rating) {
      $total += intval($rating['Rating']['value']);
    }
    $this->saveField('rating', $total);
    return true;
  }
}
?>
