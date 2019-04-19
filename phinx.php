<?php

use Phalcon\Loader;

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

return [
	'paths' => [
		'migrations' => 'db/migrations',
		'seeds' => 'db/seeds',
	],
	'environments' => [
		'default_migration_table' => 'migration',
		'production' => [
			'adapter' => 'pgsql',
			'host' => $_ENV['DATABASE_HOST'],
			'name' => $_ENV['DATABASE_NAME'],
			'user' => $_ENV['DATABASE_USERNAME'],
			'pass' => $_ENV['DATABASE_PASSWORD'],
			'port' => $_ENV['DATABASE_PORT']
		]
	]
];