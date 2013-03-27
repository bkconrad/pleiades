<?php
class Level extends AppModel {
  public $uses = array('User', 'Rating');
  public $belongsTo = array('User' => array('fields' => array('username', 'user_id')));
  public $validate = array(
    'content' => array(
      'levelgenExistence' => array(
        'rule' => 'levelgenExistence',
        'message' => 'If your level specifies a script it must also include a levelgen, otherwise the levelgen must be empty',
        'required' => true
      )
    ),
    'name' => 'notEmpty'
  );

  // checks that files have levelgens IFF the level has a script line and sets 
  // the levelgen filename as needed
  public function levelgenExistence($check) {
    $scripts = preg_grep('/^Script\s.*$/', split("\n", $this->data['Level']['content']));
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
    $this->data['Level']['levelgen'] = trim($this->data['Level']['levelgen']);
    $this->data['Level']['content'] = trim($this->data['Level']['content']);
    $this->data['Level']['name'] = trim($this->data['Level']['name']);
    $this->data['Level']['description'] = trim($this->data['Level']['description']);
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
