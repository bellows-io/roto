<?php

namespace Roto\Response;

use \Http\Response;

interface ResponseInterface {

	public function resolve(Response $response);

}