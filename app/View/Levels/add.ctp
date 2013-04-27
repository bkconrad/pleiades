<?php
echo $this->Form->create('Level', array('type' => 'file'));

if($isAdmin) {
  echo $this->Form->input('author');
}

$fileUpload = $this->Html->tag('span', $this->Form->file('contentFile'),array('class' => 'upload'));
echo $this->Form->input('content', array('between' => $fileUpload, 'placeholder' => 'Or paste code here'));

$fileUpload = $this->Html->tag('span', $this->Form->file('levelgenFile'),array('class' => 'upload'));
echo $this->Form->input('levelgen', array('between' => $fileUpload, 'placeholder' => 'Or paste code here'));

echo $this->Form->input('description');
$tagInput = $this->Form->input('Tag', array(
  'type' => 'select',
  'multiple' => 'checkbox',
));
echo $this->Html->tag('div', $tagInput, array('class' => 'tags'));
echo $this->Html->tag('label', 'Screenshot');
echo $this->Form->file('screenshot', array('label'));

echo $this->Form->end('Upload Level');
?>
