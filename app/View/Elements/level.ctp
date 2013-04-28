<?php
$divContent =
    $this->Html->link($level['Level']['name'], array('action' => 'view', $level['Level']['id']), array('class' => 'name'))
  . '<span class="author">&nbsp;by&nbsp;' . $level['User']['username'] . '</span>'
  . '<span class="rating">' . $level['Level']['rating'] . '</span>'
  . '<br>'
  . '<span class="team_count">' . $level['Level']['team_count'] . '&nbsp;Team&nbsp;</span>'
  . '<span class="game_type">' . $level['Level']['game_type'] . '</span>'
  . '<div class="thumbnail-wrapper">'
  . '<span class="thumbnail-helper">'
  . '</span>'
  . $this->Html->image('t' . $level['Level']['screenshot_filename'], array('class' => 'thumbnail'))
  . '</div>'
;
echo $this->Html->tag('div', $divContent, array('class' => 'level'));
