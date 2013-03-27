<?php
echo $this->Form->create('Level');
echo $this->Form->input('name');
echo $this->Form->input('content');
echo $this->Form->input('description');
echo $this->Form->input('levelgen');
echo $this->Tag->tagInput($level['Level']['tags']);
echo $this->Form->end('Upload Level');
?>
