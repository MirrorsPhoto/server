<?php

use Core\Plugin\PreFlightListener;
use Core\Request;
use Core\UserCenter\Security;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Events\Manager;
use Phalcon\Http\Response;
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Dispatcher;

require 'vendor/autoload.php';

$loader = new Loader();
$loader->registerDirs([
	'api/controllers/',
	'api/models/',
	'api/validations',
	'Core/helpers',
	'Core/UserCenter',
	'Core/'
]);
$loader->registerNamespaces([
	'Core\UserCenter' => 'Core/UserCenter',
	'Core\Plugin' => 'Core/Plugin',
	'Core\Enum' => 'Core/Enum',
	'Core' => 'Core',
	'Validator' => 'api/validations'
]);
$loader->register();

$di = new FactoryDefault();

$di->set('request', new Request());

$di->set(
	'dispatcher',
	function () {
		/** @var Manager $eventsManager */
		$eventsManager = $this->get('eventsManager');

		$eventsManager->attach('dispatch:beforeExecuteRoute', new PreFlightListener());
		$eventsManager->attach('dispatch:beforeExecuteRoute', new Security());

		$dispatcher = new Phalcon\Mvc\Dispatcher();

		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	}
);

$di->set('router', new Router(false));

$di->set(
	'db',
	function () {
		$connection = new Postgresql([
			'host' => $_ENV['DATABASE_HOST'],
			'username' => $_ENV['DATABASE_USERNAME'],
			'password' => $_ENV['DATABASE_PASSWORD'],
			'dbname' => $_ENV['DATABASE_NAME'],
			'port' => $_ENV['DATABASE_PORT'],
		]);

		$connection->execute('set timezone TO \'Europe/Moscow\';');

		return $connection;
	}
);

new Application($di);

try {
	/** @var Router $router */
	$router = $di->get('router');

	$router->handle($_SERVER['REQUEST_URI']);

	/** @var Dispatcher $dispatcher */
	$dispatcher = $di->get('dispatcher');

	$dispatcher->setControllerName($router->getControllerName());

	$dispatcher->setActionName($router->getActionName());

	$dispatcher->setParams($router->getParams());

	/** @var Response $response */
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
			'message' => is_array($e->getMessage()) ? $e->getMessage() : [$e->getMessage()]
		];
	}

	$response->setJsonContent($result);

	$response->sendHeaders();

	echo $response->getContent();
} catch (\Exception $e) {
	echo $e->getMessage();
}
