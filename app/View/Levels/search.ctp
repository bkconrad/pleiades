<?php
echo $this->Form->create('Level', array(
    'url' => array_merge(array('action' => 'search'), $this->params['pass'])
));
echo $this->Form->input('name', array('div' => false));
echo $this->Form->input('game_type', array('div' => false));
echo $this->Form->input('author', array('div' => false));

$tagInput = $this->Form->input('tags', array(
  'type' => 'select',
  'multiple' => 'checkbox',
));

echo $this->Html->tag('div', $tagInput, array('class' => 'tags'));

echo $this->Form->submit(__('Search'), array('div' => false));
echo $this->Form->end();
foreach ($levels as $level) {
  echo $this->element('level', array(
    'level' => $level
  ));
}
