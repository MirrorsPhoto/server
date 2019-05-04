<?php

namespace Core\Exception;

use CoreException;

class ServerError extends CoreException
{

	public function __construct(string $message = "Unknown Error", int $code = 500)
	{
		parent::__construct($message, $code);
	}
}
