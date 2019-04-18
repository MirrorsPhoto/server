<?php

namespace Core\UserCenter\Exception;

use CoreException;

class AccessDenied extends CoreException
{

	public function __construct()
	{
		parent::__construct("Access denied", 403);
	}

}