<?php
App::uses('Rating', 'Model');
App::uses('User', 'Model');
class Level extends AppModel {
  public $belongsTo = array('User' => array('fields' => array('username', 'user_id')));

  public function rate($user_id, $value) {
    $Rating = ClassRegistry::init('Rating');
    $rating = $Rating->findByUserIdAndLevelId($user_id, $this->id);

    if(empty($rating)) {
      // create a new rating
      $Rating->create();
      $Rating->set(array(
        'level_id' => $this->id,
        'user_id' => $user_id,
        'value' => $value
      ));
      $Rating->save();
    } else {
      // change the current rating
      $Rating->set(array(
        'id' => $rating["Rating"]['id'],
        'value' => $value
      ));
      $Rating->save();
    }

    // recalculate rating
    $ratings = $Rating->findAllByLevelId($this->id);
    $total = 0;
    foreach ($ratings as $rating) {
      $total += intval($rating['Rating']['value']);
    }
    return $this->saveField('rating', $total);
  }
}
?>
