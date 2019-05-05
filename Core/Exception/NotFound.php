<?php

namespace Core\Exception;

use CoreException;

class NotFound extends CoreException
{

	public function __construct(string $message = 'Not Found', int $code = 404)
	{
		parent::__construct($message, $code);
	}

}
