<?php
class UserFixture extends CakeTestFixture {
    // public $import = array('model' => 'User', 'connection' => 'forum', 'records' => false);
    // all users have password "password"
    public $primaryKey = 'user_id';
    public $name = 'User';
    public $table = 'users';
    public $fields = array(
            'user_id' => array('type' => 'integer', 'key' => 'primary'),
            'username' => 'string',
            'username_clean' => 'string',
            'user_password' => 'string'
    );
    public $records = array(
            array(
                    'user_id' => 1,
                    'username' => 'alice',
                    'username_clean' => 'alice',
                    'user_password' => '$H$9SA4lzx1SmwtQ9Xel7dhFEOP2gLOwQ1'
            ),
            array(
                    'user_id' => 2,
                    'username' => 'bob',
                    'username_clean' => 'bob',
                    'user_password' => '$H$9SA4lzx1SmwtQ9Xel7dhFEOP2gLOwQ1'
            ),
            array(
                    'user_id' => 3,
                    'username' => 'charlie',
                    'username_clean' => 'charlie',
                    'user_password' => '$H$9SA4lzx1SmwtQ9Xel7dhFEOP2gLOwQ1'
            ),
    );
}
