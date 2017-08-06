<?php

namespace Core\UserCenter\Exception;

class AccessDenied extends \CoreException
{

	public function __construct()
	{
		parent::__construct("Access denied", 401);
	}

}