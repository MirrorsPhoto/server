<?php

namespace Core\Exception;

use CoreException;
use Throwable;

class NotFound extends CoreException
{

	public function __construct($message = "Not Found", $code = 404, Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}

}