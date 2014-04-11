<?php
/**
 * LevelsTagFixture
 *
 */
class LevelsTagFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'level_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'tag_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'level_id' => 1,
			'tag_id' => 1,
			'id' => 1
		),
	);

}
