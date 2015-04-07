<?php

namespace Roto\Response;

use \Http\Response;
use \Http\StatusCode;

class AttachmentResponse implements ResponseInterface {

	protected $filename;
	protected $contents;

	public function __construct($filename, $contents) {
		$this->filename     = $filename;
		$this->contents = $contents;
	}

	public function resolve(Response $response) {

		$response->setCode(StatusCode::STATUS_200);
		$response->setHeader('Content-disposition', 'attachment; filename='.$this->filename);

		$response->lazySetBody(function() {
			echo $this->contents;
		});
	}
}
