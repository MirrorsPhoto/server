<?php
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
