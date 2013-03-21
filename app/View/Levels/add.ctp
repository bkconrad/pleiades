<?php
echo "<h1>User:" . $user . "<h1>";
echo $this->Form->create('Level');
echo $this->Form->input('name');
echo $this->Form->input('content');
echo $this->Form->end('Upload Level');
?>
