<?php

namespace Core\Exception;

use Throwable;

class ServerError extends \CoreException
{

	public function __construct($message = "Unknown Error", $code = 500, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}