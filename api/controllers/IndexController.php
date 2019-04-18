<?php

use Core\Exception\NotFound;

/**
 * @RoutePrefix('/index')
 */
class IndexController extends Controller
{

	/**
	 * @Get('/index')
	 *
	 * @return int
	 */
	public function indexAction()
	{
		return 1;
	}

	/**
	 * @throws NotFound
	 * @return void
	 */
	public function notFoundAction()
	{
		throw new NotFound();
	}
}
