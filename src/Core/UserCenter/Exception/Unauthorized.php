<?php

namespace Core\UserCenter\Exception;

use CoreException;

class Unauthorized extends CoreException
{

	public function __construct(string $message = 'auth.required')
	{
		parent::__construct($message, 401);
	}

}
