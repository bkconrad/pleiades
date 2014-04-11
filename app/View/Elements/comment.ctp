<?php
$deleteButton = '';

if($isAdmin || $comment['Comment']['user_id'] == $currentUserId || $comment['Level']['user_id'] == $currentUserId)
	$deleteButton = $this->Html->link('&times;', '/comments/delete/' . $comment['Comment']['id'], array('class' => 'comment-delete', 'escape' => false));

$divContent = $deleteButton
    . $this->Html->tag('div', $comment['User']['username'], array('class' => 'comment-author'))
    . $this->Html->tag('div', $comment['Comment']['text'], array('class' => 'comment-text', 'escape' => true));

echo $this->Html->tag('div', $divContent, array('class' => 'comment'));
