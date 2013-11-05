<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

	public function afterSave($created, $options = array()) {
		parent::afterSave($created, $options);
		$this->touch($this->id);
	}

	public function afterDelete() {
		parent::afterDelete();
		$this->touch($this->id);
	}

	/**
	 * "touch" this model's cached last update time, and optionally the cached
	 * last update time of the record with the given id.
	 */
	public function touch($id = null) {
		$now = time();
		Cache::write($this->name . '_last_update', $now);
		if($id) {
			Cache::write($this->name . '_last_update' . '_' . $id, $now);
		}
		return $now;
	}

	/**
     * Returns the cached last update time for the entity, or the given record
     * if id is not null.
	 */
	public function last($id = null) {
		$last = null;

		if($id !== null) {
			$last = Cache::read("{$this->name}_last_update_$id");
		} else {
			$last = Cache::read("{$this->name}_last_update");
		}

		if($last === null)
			$last = $this->touch($id);

		return $last;
	}
}
