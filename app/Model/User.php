<?php
class User extends AppModel{
	var $name = 'User';
	var $primaryKey = 'user_id';
	var $useDbConfig = 'forum';
  var $displayField = 'username';

  /**
   * Return an array of the groups that this user belongs to
   */
  public function getGroups() {
    $groups = $this->query('SELECT group_id FROM phpbb_user_group WHERE user_id = ' . $this->id . ';');
    $result = array();
    foreach($groups as $group) {
      array_push($result, $group['phpbb_user_group']['group_id']);
    }
    return $result;
  }
}
?>
