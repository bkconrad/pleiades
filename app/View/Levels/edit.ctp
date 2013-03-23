<?php
echo $this->Form->create('Level');
echo $this->Form->input('name');
echo $this->Form->input('description');
echo $this->Form->input('content');
echo $this->Form->input('levelgen');
echo $this->Form->end('Update Level');
?>
