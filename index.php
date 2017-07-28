<?php
use Phalcon\Loader;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;

$di = new FactoryDefault();
$di->set('config', new Ini('api/config/config.ini'));
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