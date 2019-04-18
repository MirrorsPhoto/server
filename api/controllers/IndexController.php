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

	/**
	 * @Get('/deploy')
	 *
	 * @return string
	 */
	public function deployAction()
	{
		return exec('git pull && php composer.phar update && ./vendor/phalcon/devtools/phalcon.php migration run --config=./api/config/config.ini');
	}

}