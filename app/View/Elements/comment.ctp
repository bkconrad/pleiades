<?php
$divContent =
      $this->Html->tag('div', $comment['User']['username'], array('class' => 'comment-author'))
    . $this->Html->tag('div', $comment['Comment']['text'], array('class' => 'comment-text', 'escape' => true));

echo $this->Html->tag('div', $divContent, array('class' => 'comment'));
