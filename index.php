<?php
use Phalcon\Loader;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;

$di = new FactoryDefault();

$debug = (new \Phalcon\Debug())->listen();

$loader = new Loader();

$loader->registerDirs(
	[
		'../api/controllers/',
		'../api/models/',
		'Helpers/'
	]
)->register();

$application = new Application($di);

try {
	$response = $application->handle();

	$response->send();
} catch (\Exception $e) {
	echo $e->getMessage();
}