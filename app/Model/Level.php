<?php
class Level extends AppModel {
  public $belongsTo = array('User' => array('fields' => array('username', 'user_id')));

  public function rate($level_id, $user_id, $value) {
    $Rating = ClassRegistry::init('Rating');
    $rating = $Rating->find('first', array(
      "conditions" => array(
        "Rating.level_id = $level_id",
        "Rating.user_id = $user_id"
        )
      )
    );

    if(empty($rating)) {
      // create a new rating
      $Rating->create();
      $Rating->set(array(
        'level_id' => $level_id,
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
    $ratings = $Rating->findAllByLevelId($level_id);
    $total = 0;
    foreach ($ratings as $rating) {
      $total += intval($rating['Rating']['value']);
    }
    $this->id = $level_id;
    return $this->saveField('rating', $total);
  }
}
?>
