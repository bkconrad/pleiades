<?php
App::uses('AppController', 'Controller');
/**
 * Comments Controller
 *
 */
class CommentsController extends AppController {

	public function add() {
		$this->Comment->set($this->request->data['Comment']);
		$this->Comment->set('user_id', $this->Auth->user('user_id'));

		if(!$this->Comment->save()) {
			throw new BadRequestException("Unable to save comment.");
		}

		$this->Session->setFlash('Comment added successfully');
		return $this->redirect($this->referer());
	}
}
