<?php
echo $this->Form->create('Level', array('type' => 'file'));
echo $this->Form->input('name');

$fileUpload = $this->Html->tag('span', $this->Form->file('contentFile'),array('class' => 'upload'));
echo $this->Form->input('content', array('between' => $fileUpload, 'placeholder' => 'Or paste code here'));

$fileUpload = $this->Html->tag('span', $this->Form->file('levelgenFile'),array('class' => 'upload'));
echo $this->Form->input('levelgen', array('between' => $fileUpload, 'placeholder' => 'Or paste code here'));

echo $this->Form->input('description');
echo $this->Form->input('Tag', array(
  'type' => 'select',
  'multiple' => 'checkbox',
  'options' => $tags
));

echo $this->Form->end('Upload Level');
?>
