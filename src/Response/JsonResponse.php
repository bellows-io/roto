<?php

namespace Roto\Response;

use \Http\Response;
use \Http\StatusCode;

class JsonResponse implements ResponseInterface {

	protected $data;
	protected $prettyPrint;

	public function __construct($data, $prettyPrint = false) {
		$this->data      = $data;
		$this->prettyPrint = $prettyPrint;
	}

	public function resolve(Response $response) {

		$response->setCode(StatusCode::STATUS_200);
		$response->setHeader('Content-type', 'application/json');
		$response->lazySetBody(function() {

			echo json_encode($this->data, $this->prettyPrint ? \JSON_PRETTY_PRINT : null);

		});
	}
}