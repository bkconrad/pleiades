<?php
class UserFixture extends CakeTestFixture {
  public $import = array('model' => 'User', 'table' => 'users', 'connection' => 'forum');
  // all users have password "password"
  public $records = array(
    array(
      'user_id' => 1,
      'username' => 'alice',
      'user_password' => '$H$9SA4lzx1SmwtQ9Xel7dhFEOP2gLOwQ1'
    ),
    array(
      'user_id' => 2,
      'username' => 'bob',
      'user_password' => '$H$9SA4lzx1SmwtQ9Xel7dhFEOP2gLOwQ1'
    ),
  );
}
