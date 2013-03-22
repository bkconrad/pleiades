<div>
<?php
echo $this->Html->link('View All Levels', array(
  'controller' => 'levels',
  'action' => 'index'
)); 

echo '&nbsp;';

if($currentUserId) {
  echo $this->Html->link('Upload Level', array(
    'controller' => 'levels',
    'action' => 'add'
  ));

  echo '&nbsp;';

  echo $this->Html->link('Logout', array(
    'controller' => 'users',
    'action' => 'logout'
  ));
} else {
  echo $this->Html->link('Login', array(
    'controller' => 'users',
    'action' => 'login'
  ));
}
?>
</div>
