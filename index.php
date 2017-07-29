<?php
use Phalcon\Loader;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Factory;

require 'vendor/autoload.php';

(new Loader())->registerDirs(
	[
		'api/controllers/',
		'api/models/',
		'Helpers/'
	]
)->register();

$debug = (new \Phalcon\Debug())->listen();

$di = new FactoryDefault();
$di->set('config', new Ini('api/config/config.ini'));

$di->set(
	'db',
	function () {
		return Factory::load($this->get('config')->database);
	}
);

$application = new Application($di);

try {
	$router = $di->get('router');

	$router->handle();

	$dispatcher = $di->get('dispatcher');

	$dispatcher->setControllerName($router->getControllerName());

	$dispatcher->setActionName($router->getActionName());

	$dispatcher->setParams($router->getParams());

	$response = $di->get('response');

	try {
		$dispatcher->dispatch();

		$result = [
			'status' => 'OK',
			'response' => $dispatcher->getReturnedValue()
		];
	} catch (Exception $e) {
		$response->setStatusCode($e->getCode());

		$result = [
			'status' => 'ERROR',
			'message' => $e->getMessage()
		];
	}

	$response->setJsonContent($result);

	$response->sendHeaders();

	echo $response->getContent();
} catch (\Exception $e) {
	echo $e->getMessage();
}