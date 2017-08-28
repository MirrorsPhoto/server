<?php
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Factory;

require 'vendor/autoload.php';

(new Loader())
	->registerDirs([
		'api/controllers/',
		'api/models/',
		'api/validations',
		'Core/helpers',
		'Core/UserCenter',
		'Core/'
	])
	->registerNamespaces([
		'Core\UserCenter' => 'Core/UserCenter',
		'Core\Plugin' => 'Core/Plugin',
		'Core\Enum' => 'Core/Enum',
		'Core' => 'Core',
		'Validator' => 'api/validations'
	])->register();

$debug = (new \Phalcon\Debug())->listen();

$di = new FactoryDefault();
$di->set('config', ConfigIni::getInstance());

$di->set(
	'dispatcher',
	function () {
		$eventsManager = $this->get('eventsManager');

		$eventsManager->attach('dispatch:beforeExecuteRoute', new \Core\Plugin\PreFlightListener());
		$eventsManager->attach('dispatch:beforeExecuteRoute', new \Core\UserCenter\Security());

		$dispatcher = new Phalcon\Mvc\Dispatcher();

		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	}
);

$di->set('router', new Router(false));

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
		$statusCode = $e->getCode() ? $e->getCode() : 500;

		$response->setStatusCode($statusCode);

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