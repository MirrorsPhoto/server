<?php

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

	/**
	 * @Get('/deploy')
	 */
	public function deployAction()
	{
		return exec('git pull && php composer.phar update && ./vendor/phalcon/devtools/phalcon.php migration run --config=./api/config/config.ini');
	}

}