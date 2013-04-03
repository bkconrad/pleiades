<?php
foreach($levelLists as $heading => $levels) {
  echo $this->Html->tag('h1', $heading);
  foreach ($levels as $k => $level) {
    $divContent =
        $this->Html->link($level['Level']['name'], array('action' => 'view', $level['Level']['id']))
      . '&nbsp; by ' . $level['User']['username']
      . '<span class="rating">' . $level['Level']['rating'] . '</span>'
      . '&nbsp;' . $this->Html->image('t' . $level['Level']['screenshot_filename'], array('class' => 'thumbnail'))
    ;
    echo $this->Html->tag('div', $divContent, array('class' => 'level'));
  }
}

?>
