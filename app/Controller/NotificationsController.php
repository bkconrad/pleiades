<?php
App::uses('AppController', 'Controller');
/**
 * Notifications Controller
 *
 */
class NotificationsController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny();

        if($this->Auth->loggedIn()) {
	        $this->Auth->allow('index', 'delete', 'clear');
        }
    }

	public function index() {
		$notifications = $this->Notification->find('all', array('conditions' => array('user_id' => $this->Auth->user('user_id'))));
		$this->set('notifications', $notifications);
	}

	public function delete($id) {
		$notification = $this->Notification->findById($id);
		if($notification['User']['user_id'] !== $this->Auth->user('user_id')) {
			throw new ForbiddenException('This is not your notification');
		}

		$redirect = $notification['Notification']['url'];
		$this->Notification->delete($notification['Notification']['id']);
		return $this->redirect($redirect);
	}

	public function clear() {
		$this->Notification->deleteAll(array('user_id' => $this->Auth->user('user_id')));
		$this->Session->setFlash('Notifications cleared.');
		return $this->redirect($this->referrer);
	}
}
