<?php
foreach($levelLists as $heading => $levels) {
  echo $this->Html->tag('h1', $heading);
  foreach ($levels as $k => $level) {
    echo $this->element('level', array(
      'level' => $level
    ));
  }
}

?>
