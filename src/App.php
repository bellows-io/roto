<?php

namespace Roto;

use \Http\StatusCode;
use \Http\Response as HttpResponse;

use \RotoHttp\Response\ViewResponse;
use \RotoHttp\Response\ResponseInterface;

class App {

	protected $injector;
	protected $verbose;

	protected $controller = null;
	protected $action = null;

	public function __construct($injector, $verbose = false) {
		$this->injector = $injector;
		$this->verbose = $verbose;
	}

	public function getController() {
		return $this->controller;
	}

	public function getAction() {
		return $this->action;
	}

	protected function getResponse($controllerPath, $action) {
		$httpResponse = new HttpResponse;

		try {
			if (! class_exists($controllerPath)) {
				throw new RoutingException(StatusCode::STATUS_404, "Invalid Controller");
			}
			try {
				$controller = $this->injector->invokeConstructor($controllerPath);
			} catch (\Exception $ex) {
				throw new RoutingException(StatusCode::STATUS_500, "Could not construct controller", $ex->getCode(), $ex);
			}
			if (! method_exists($controller, $action)) {
				throw new RoutingException(StatusCode::STATUS_404, "Invalid action");
			}
			try {
				$actionResponse = $this->injector->invokeMethod($controller, $action);
			} catch (\Exception $ex) {
				throw new RoutingException(StatusCode::STATUS_500, "Could not invoke action", $ex->getCode(), $ex);
			}

			if (! ($actionResponse instanceof ResponseInterface)) {
				throw new RoutingException(StatusCode::STATUS_500, "Invalid controller response");
			}

			$actionResponse->resolve($httpResponse);

		} catch (RoutingException $ex) {
			$httpResponse->setCode($ex->getStatusCode());
			$httpResponse->setBody($ex->getMessage());

		}
		return $httpResponse;
	}

	public function run($request, $router) {

		$uriPath = parse_url($request->getUri())['path'];
		list($this->controller, $this->action) = $router->route($uriPath, $pathName);

		$response = $this->getResponse($this->controller, $this->action);

		$response->eachHeader('header');
		$response->echoBody();
	}

}