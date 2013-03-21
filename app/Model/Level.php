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
      return $Rating->save();
    } else {
      // change the current rating
      $Rating->set(array(
        'id' => $rating["Rating"]['id'],
        'value' => $value
      ));
      return $Rating->save();
    }
  }
}
?>
