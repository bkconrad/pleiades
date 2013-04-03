<?php
foreach($levelLists as $heading => $levels) {
  echo $this->Html->tag('h1', $heading);
  echo '
  <table>
  <th>Name</th><th>Uploader</th><th>Rating</th>';
  foreach ($levels as $k => $level) {

    $options = array('escape' => true);
    $rowdata = array(
      $this->Html->link($level['Level']['name'], array('action' => 'view', $level['Level']['id'])),
      $level['User']['username'],
      $level['Level']['rating'],
      $this->Html->image('t' . $level['Level']['screenshot_filename'])
    );
    echo $this->Html->tableCells(array($rowdata), $options, $options);
  }
  echo '</table>';
}

?>
