<?php
class UserGroupFixture extends CakeTestFixture {
    public $fields = array(
            'id' => array('type' => 'integer', 'key' => 'primary'),
            'user_id' => 'integer',
            'group_id' => 'integer'
    );
    public $useDbConfig = 'test_forum';
    public $table = 'phpbb_user_group';
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
