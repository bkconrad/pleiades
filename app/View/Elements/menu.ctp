<div id="navmenu">
<?php
echo $this->Html->link('Home', array(
  'controller' => 'levels',
  'action' => 'index'
)); 

echo '&nbsp;';

echo $this->Html->link('Search', array(
  'controller' => 'levels',
  'action' => 'search'
)); 

echo '&nbsp;';

if($currentUserId) {
  echo $this->Html->link('Upload Level', array(
    'controller' => 'levels',
    'action' => 'add'
  ));

  echo '&nbsp;';

  echo $this->Html->link('Profile', array(
    'controller' => 'users',
    'action' => 'view',
    $currentUserId
  ));

  echo '&nbsp;';

  echo $this->Html->link('Notifications', array(
    'controller' => 'notifications',
    'action' => 'index'
  ));

  if($notificationCount > 0) {
    // set in AppController
    echo $this->Html->tag('span', $notificationCount, array('class' => 'notification-count label lbl-default'));
  }

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
