<?php

namespace Roto\Response;

use RotoHttp\View\View;
use RotoHttp\View\Dispatcher;
use RotoHttp\View\Layout;

use \Http\Response;
use \Http\StatusCode;

class ViewResponse implements ResponseInterface {

	protected $view = null;
	protected $layout = null;

	public function __construct(View $view, Layout $layout = null) {
		$this->view   = $view;
		$this->layout = $layout;
	}

	public function resolve(Response $response) {

		$response->setCode(StatusCode::STATUS_200);
		$response->lazySetBody(function() {
			if ($this->view) {
				if ($this->layout->hasSource()) {
					$this->layout->render($this->view);
				} else {
					$this->view->render();
				}
			} else {
				throw new \Exception("Missing view");
			}
		});
	}
}