<?php
App::uses('AppModel', 'Model');
App::uses('Level', 'Model');

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
                    'message' => 'The specified level does not exist',
                    'required' => true
            ),
            'user_id' => array(
                    'rule' => 'userExists',
                    'message' => 'The specified user does not exist',
                    'required' => true
            )
    );

    public $uses = array('Level');

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

    public function afterSave($created, $options = array()) {
       parent::afterSave($created, $options); 
       $this->updateLevelCommentCount();
    }

    public function beforeDelete($cascade = true) {
       parent::beforeDelete($cascade); 
       $this->updateLevelCommentCount(-1);
       return true;
    }

    private function updateLevelCommentCount($modifier = 0) {
       $levelId = $this->field('level_id');
       $commentCount = $this->find('count', array('conditions' => array('level_id' => $levelId)));
       
       $this->Level->save(array('id' => $levelId, 'comment_count' => $commentCount + $modifier));
    }
}
