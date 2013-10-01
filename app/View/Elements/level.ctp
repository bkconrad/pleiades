<?php
$tagList = '';
foreach($level['Tag'] as $k => $tag) {
  $link = $this->Html->link($tag['name'], '/levels/search/tags[0]:' . $tag['id'] , array('escape' => true));
  $tagList .= $this->Html->tag('li', $link, array('class' => 'tag'));
}

$divContent =
    '<div class="level">'
  . $this->Html->link($level['Level']['name'], array('action' => 'view', $level['Level']['id']), array('class' => 'name', 'title' => $level['Level']['name']))
  . '<span class="rating">' . $level['Level']['rating'] . '</span>'
  . '<span class="author">by&nbsp;' . $this->Html->link($level['User']['username'], '/levels/search/author:' . $level['User']['username']) . '</span>'
  . '<span class="level_info">'
  . '<span class="team_count">' . $level['Level']['team_count'] . '&nbsp;Team&nbsp;</span>'
  . '<span class="game_type">' . $level['Level']['game_type'] . '</span>'
//  . '<span class="levelgen">' . (empty($level['Level']['levelgen_filename']) ? '' : '&nbsp;-&nbsp;LG') .  '</span>'
  . '</span>'
  . $this->Html->link($this->Html->image('t' . $level['Level']['screenshot_filename'], array('class' => 'thumbnail')), '/levels/view/' . $level['Level']['id'], array('class' => 'thumbnail-wrapper', 'escape' => false))
  . $this->Html->tag('ul', $tagList, array('class' => 'small-tags'))
  . '</div>'
;
echo $this->Html->tag('div', $divContent, array('class' => 'level-container col-3 col-sm-6 col-lg-3'));
