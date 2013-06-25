<?php
App::uses('AppModel', 'Model');
/**
 * Comment Model
 *
 * @property User $User
 * @property Level $Level
 */
class Comment extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'text';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Level' => array(
			'className' => 'Level',
			'foreignKey' => 'level_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

    public $validate = array(
        'level_id' => array(
            'rule' => 'levelExists',
            'message' => 'The specified level does not exist'
        ),
        'user_id' => array(
            'rule' => 'userExists',
            'message' => 'The specified user does not exist'
        )
    );

    public function levelExists() {
        if (!isset($this->data['Comment']) || !isset($this->data['Comment']['level_id'])) {
            return false;
        }

        $id = $this->data['Comment']['level_id'];
        $result = $this->Level->findById($id);
        return !empty($result['Level']);
    }

    public function userExists() {
        if (!isset($this->data['Comment']) || !isset($this->data['Comment']['user_id'])) {
            return false;
        }

        $id = $this->data['Comment']['user_id'];
        $result = $this->User->findByUserId($id);
        return !empty($result['User']);
    }
}
