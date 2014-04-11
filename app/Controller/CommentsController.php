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
    
    public function delete($id) {
    	$comment = $this->Comment->findById($id);
    	if(!$comment) {
    		throw new NotFoundException("The specified comment could not be found");
    	}

    	if(
    		$comment['Level']['user_id'] != $this->Auth->user('user_id') &&
			$comment['Comment']['user_id'] != $this->Auth->user('user_id') &&
			!$this->isAdmin()
		) {
			throw new ForbiddenException("You can not delete this comment.");
    	}

    	$this->Comment->delete($id);
        return $this->redirect($this->referer());
    }
}
