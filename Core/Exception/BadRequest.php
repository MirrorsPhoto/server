<?php

namespace Core\Exception;

use CoreException;

class BadRequest extends CoreException
{

	public function __construct(string $message = "Bad Request", int $code = 400)
	{
		parent::__construct($message, $code);
	}

}
