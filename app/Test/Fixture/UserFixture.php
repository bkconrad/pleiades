<?php
class UserFixture extends CakeTestFixture {
  public $import = array('model' => 'User', 'table' => 'users', 'connection' => 'forum');
  public $records = array(array(
    'user_id' => 1,
    'username' => 'user'
  ));
}
