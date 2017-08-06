<?php

namespace Core\UserCenter\Exception;

class Unauthorized extends \CoreException
{

	public function __construct()
	{
		parent::__construct("WWW-Authenticate", 401);
	}

}