<?php
use Phalcon\Loader;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;

$di = new FactoryDefault();
$di->set('config', new Ini('api/config/config.ini'));
$debug = (new \Phalcon\Debug())->listen();

(new Loader())->registerDirs(
	[
		'../api/controllers/',
		'../api/models/',
		'Helpers/'
	]
)->register();

$application = new Application($di);

try {
	$response = $application->useImplicitView(false);
	
	$response->handle();
} catch (\Exception $e) {
	echo $e->getMessage();
}