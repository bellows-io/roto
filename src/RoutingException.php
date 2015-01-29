<?php

namespace Roto;

class RoutingException extends \Exception {

	protected $statusCode;

	public function __construct($statusCode, $message = '', $errorCode = 0, \Exception $previous = NULL) {
		$this->statusCode = $statusCode;
		parent::__construct($message, $errorCode, $previous);
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

}