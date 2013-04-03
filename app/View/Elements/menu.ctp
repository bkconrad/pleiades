<div id="navmenu">
<?php
echo $this->Html->link('Home', array(
  'controller' => 'levels',
  'action' => 'index'
)); 

echo '&nbsp;';

echo $this->Html->link('View All Levels', array(
  'controller' => 'levels',
  'action' => 'all'
)); 

echo '&nbsp;';

if($currentUserId) {
  echo $this->Html->link('Upload Level', array(
    'controller' => 'levels',
    'action' => 'add'
  ));

  echo '&nbsp;';

  echo $this->Html->link("Logout [$currentUserName]", array(
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
