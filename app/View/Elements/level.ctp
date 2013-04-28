<?php
$tagList = '';
foreach($level['Tag'] as $k => $tag) {
  $tagList .= $this->Html->tag('li', $tag['name'], array('escape' => true, 'class' => 'tag'));
}

$divContent =
    $this->Html->link($level['Level']['name'], array('action' => 'view', $level['Level']['id']), array('class' => 'name'))
  . '<span class="author">&nbsp;by&nbsp;' . $level['User']['username'] . '</span>'
  . '<span class="rating">' . $level['Level']['rating'] . '</span>'
  . '<br>'
  . '<span class="team_count">' . $level['Level']['team_count'] . '&nbsp;Team&nbsp;</span>'
  . '<span class="game_type">' . $level['Level']['game_type'] . '</span>'
  . '<span class="levelgen">' . (empty($level['Level']['levelgen_filename']) ? '' : '&nbsp;-&nbsp;LG') .  '</span>'
  . '<div class="thumbnail-wrapper">'
  . '<span class="thumbnail-helper">'
  . '</span>'
  . $this->Html->image('t' . $level['Level']['screenshot_filename'], array('class' => 'thumbnail'))
  . '</div>'
  . $this->Html->tag('ul', $tagList, array('class' => 'small-tags'))
;
echo $this->Html->tag('div', $divContent, array('class' => 'level'));
