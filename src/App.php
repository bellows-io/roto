<?php

namespace Roto;

use \Http\StatusCode;
use \Http\Response as HttpResponse;

use \Roto\Response\ViewResponse;
use \Roto\Response\RedirectResponse;
use \Roto\Response\ResponseInterface;

class App {

	protected $injector;
	protected $verbose;
	protected $redirector;
	protected $router;

	protected $controller = null;
	protected $action = null;

	public function __construct($injector, $router, $redirector, $verbose = false) {
		$this->injector   = $injector;
		$this->verbose    = $verbose;
		$this->router     = $router;
		$this->redirector = $redirector;
	}

	public function getController() {
		return $this->controller;
	}

	public function getAction() {
		return $this->action;
	}

	protected function respond($httpResponse, $controllerPath, $action) {

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
			throw new RoutingException(StatusCode::STATUS_500, "Could not invoke action: ".$ex->getMessage(), $ex->getCode(), $ex);
		}

		if (! ($actionResponse instanceof ResponseInterface)) {
			throw new RoutingException(StatusCode::STATUS_500, "Invalid controller response");
		}

		$actionResponse->resolve($httpResponse);
	}

	public function run($request) {

		$uriPath = parse_url($request->getUri())['path'];
		$httpResponse = new HttpResponse;
		try {

			if ( $url = $this->redirector->route( $uriPath, $code ) ) {
				$redirect = new RedirectResponse( $url, $code == 301 );
				$redirect->resolve( $httpResponse );
			} else {

				list($this->controller, $this->action) = $this->router->route($uriPath, $pathName);

				$this->respond($httpResponse, $this->controller, $this->action);
			}
		} catch ( RoutingException $ex ) {
			$httpResponse->setCode($ex->getStatusCode());
			$httpResponse->setBody($ex->getMessage());
		}

		$httpResponse->eachHeader('header');
		$httpResponse->echoBody();
	}

}