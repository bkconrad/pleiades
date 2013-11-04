<?php
App::uses('ExceptionRenderer', 'Error');

/**
 * Custom ExceptionRenderer for displaying Exception information to the
 * bitfighter client. Whenever the User-Agent header of a request is exactly the
 * string 'Bitfighter', we set the body to exactly the 'message' of the
 * Exception which is raised. Otherwise, we use the default ExceptionRenderer.
 */
class AppExceptionRenderer extends ExceptionRenderer {
	public function render() {
        if($this->controller->request->header('User-Agent') == 'Bitfighter') {
			$this->controller->response->body($this->error->getMessage());
			$this->controller->response->type('text');

			if($this->error instanceof HttpException) {
				$this->controller->response->statusCode($this->error->getCode());
			} else {
				$this->controller->response->statusCode(500);
			}

			$this->controller->response->send();
		} else {
			parent::render();
		}
	}
}