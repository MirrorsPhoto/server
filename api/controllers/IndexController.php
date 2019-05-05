<?php

use Core\Exception\NotFound;

/**
 * @RoutePrefix('/index')
 */
class IndexController extends Controller
{

	/**
	 * @throws NotFound
	 * @return void
	 */
	public function notFoundAction()
	{
		throw new NotFound();
	}
}
