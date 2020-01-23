<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;
use Phalcon\Db\Adapter\Pdo\Postgresql;

require 'vendor/autoload.php';

$loader = new Loader();
$loader->registerDirs([
	'models/',
	'tasks/',
	'Core/helpers',
	'Core/',
]);
$loader->registerNamespaces([
	'Core\Plugin' => 'Core/Plugin',
	'Core\Enum' => 'Core/Enum',
	'Core' => 'Core',
]);
$loader->register();

$console = new ConsoleApp();

$di = new CliDI();

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

$console->setDI($di);

/**
 * Обработка аргументов консоли
 */
$arguments = [];

foreach ($argv as $k => $arg) {
	if ($k === 1) {
		$arguments['task'] = $arg;
	} elseif ($k === 2) {
		$arguments['action'] = $arg;
	} elseif ($k >= 3) {
		$arguments['params'][] = $arg;
	}
}

try {
	$console->handle($arguments);
} catch (\Phalcon\Exception $e) {
	fwrite(STDERR, $e->getMessage() . PHP_EOL);
	exit(1);
} catch (\Throwable $throwable) {
	fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
	exit(1);
} catch (\Exception $exception) {
	fwrite(STDERR, $exception->getMessage() . PHP_EOL);
	exit(1);
}