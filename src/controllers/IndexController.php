<?php

use Core\Exception\NotFound;

/**
 * @RoutePrefix('/index')
 */
class IndexController extends Controller
{

	/**
	 * @throws NotFound
	 */
	public function notFoundAction(): void
	{
		throw new NotFound();
	}

}
