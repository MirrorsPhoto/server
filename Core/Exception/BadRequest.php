<?php

namespace Core\Exception;

use Throwable;

class BadRequest extends \CoreException
{

	public function __construct($message = "Bad Request", $code = 400, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}