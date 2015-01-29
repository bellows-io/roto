<?php

namespace Roto\Response;

use \Http\Response;
use \Http\StatusCode;

class CsvResponse implements ResponseInterface {

	protected $data;
	protected $delimiter;

	public function __construct($data, $delimiter = ",") {
		$this->data      = $data;
		$this->delimiter = $delimiter;
	}

	public function resolve(Response $response) {

		$response->setCode(StatusCode::STATUS_200);
		$response->setHeader('Content-type', 'text/csv');
		$response->lazySetBody(function() {

			$stream = fopen('php://output', 'w');
			$headersSet = false;
			foreach ($this->data as $row) {
				if (! $headersSet) {
					$headers = array_keys($row);
					fputcsv($stream, $headers, $this->delimiter);
					$headersSet = true;
				}
				fputcsv($stream, $row, $this->delimiter);
			}

		});
	}
}