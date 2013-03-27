<?php
// enum class of tags and names
final class Tags {
  public $novelty = 1;
  public $dungeon = 2;
  public $workInProgress = 4;
}

App::uses('AppHelper', 'View/Helper');
class TagHelper extends AppHelper {
  public function tags($int) {
    $result = array();
    foreach(new Tags() as $name => $bit) {
      if ($int & $bit) {
        array_push($result, $name);
      }
    }
    return $result;
  }

  public function allTags() {
    $tags = array();
    foreach(new Tags() as $name => $bit) {
        array_push($tags, $name);
    }
    return $tags;
  }
}
