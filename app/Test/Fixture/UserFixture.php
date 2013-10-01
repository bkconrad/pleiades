<?php
class UserFixture extends CakeTestFixture {
    public $import = array('model' => 'User', 'connection' => 'forum', 'records' => false);
    // all users have password "password"
    public $useDbConfig = 'test_forum';
    public $primaryKey = 'user_id';
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
            array(
                    'user_id' => 3,
                    'username' => 'charlie',
                    'user_password' => '$H$9SA4lzx1SmwtQ9Xel7dhFEOP2gLOwQ1'
            ),
    );
}
