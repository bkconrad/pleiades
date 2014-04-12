<?php
if(empty($notifications)) {
	echo 'No notifications found.';
} else {
	echo $this->Html->link('Clear All', array('action' => 'clear'));
	foreach($notifications as $notification) {
		echo $this->element('notification', array('notification' => $notification['Notification']));
	}
}

?>
