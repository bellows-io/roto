<?php

namespace Roto\Response;

use \Http\Response;
use \Http\StatusCode;

class RedirectResponse implements ResponseInterface {

	protected $location;
	protected $isPermanent;

	public function __construct($location, $isPermanent = false) {
		$this->location = $location;
		$this->isPermanent = $isPermanent;
	}

	public function resolve(Response $response) {

		$response->setCode($isPermanent ? StatusCode::STATUS_301 : StatusCode::STATUS_304);
		$response->setHeader("Location", $this->location);

		$response->setBody(sprintf("Redirecting to `%s'", $this->location));
	}

}