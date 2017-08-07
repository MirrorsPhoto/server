<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

	public function indexAction()
	{
		return 1;
	}

	public function notFoundAction()
	{
		throw new \Core\Exception\NotFound();
	}

}