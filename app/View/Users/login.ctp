<?php
echo $this->Session->flash('auth');
echo $this->Form->create('User');
echo $this->Form->input('username');
echo $this->Form->input('user_password',array('type'=>'password','label'=>'Password'));
echo $this->Form->end('Login',array('value'=>'Accedi'));
?>
