<?php

namespace Roto\Request;

use \Http\Request as HttpRequest;

class Request {

	protected $request;
	protected $controller;
	protected $action;

	public function __construct(HttpRequest $request, $controller, $action) {
		$this->request = $reqeuest;
		$this->controller = $controller;
		$this->action = $action;
	}

	public function getRequest() {
		return $this->request;
	}

	public function getController() {
		return $this->controller;
	}

	public function getAction() {
		return $this->action;
	}



}