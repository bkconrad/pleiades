<?php
class UserGroupFixture extends CakeTestFixture {
  public $import = array('table' => 'user_group', 'connection' => 'forum', 'records' => false);
  public $table = 'pleiades_test.phpbb_user_group';
  public $records = array(
    array(
      'user_id' => 1,
      'group_id' => 1
    ),
    array(
      'user_id' => 2,
      'group_id' => 1
    ),
    array(
      'user_id' => 2,
      'group_id' => 2
    ),
  );
}
