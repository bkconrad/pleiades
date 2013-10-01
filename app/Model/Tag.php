<?php
App::uses('AppModel', 'Model');
/**
 * Tag Model
 *
 */
class Tag extends AppModel {
	public $hasAndBelongsToMany = array('Level');
}
