<?php

use Phalcon\Mvc\Controller;

/**
 * @RoutePrefix('/index')
 */
class IndexController extends Controller
{

	/**
	 * @Get('/index')
	 */
	public function indexAction()
	{
		return 1;
	}

	public function notFoundAction()
	{
		throw new \Core\Exception\NotFound();
	}

}