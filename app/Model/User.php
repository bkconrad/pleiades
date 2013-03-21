<?php
class User extends AppModel{
	var $name = 'User';
	var $primaryKey = 'user_id';
	var $useDbConfig = 'forum';
  var $displayField = 'username';
}
?>
