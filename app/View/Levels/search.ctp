<h1>Advanced Search</h1>
<?php
echo $this->Form->create('Level', array(
    'url' => array_merge(array('action' => 'search'), $this->params['pass'])
));
echo $this->Form->input('name');

$gameTypeOptions = Configure::read('App.gametype_prefix_to_pretty_name_map');
$gameTypeOptions = array_combine($gameTypeOptions, $gameTypeOptions); 
echo $this->Form->input('game_type', array(
	'options' => $gameTypeOptions
));
echo $this->Form->input('author');

$tagInput = $this->Form->input('tags', array(
  'type' => 'select',
  'multiple' => 'checkbox',
));

echo $this->Html->tag('div', $tagInput, array('class' => 'tags'));

echo $this->Form->submit(__('Search'), array('div' => false));
echo $this->Form->end();
if(count($levels) > 0) {
  echo '<h1>Results</h1>';
}
foreach ($levels as $level) {
  echo $this->element('level', array(
    'level' => $level
  ));
}
