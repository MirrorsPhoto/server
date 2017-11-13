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

$config = ConfigIni::getInstance();

if (!isset($config)) {
    die('You must create config first in: ' . $config . PHP_EOL);
} else {
    $database = $config->database;

    if ($database === null) {
        die('In your config you not set a database key' . PHP_EOL);
    }

    if (!isset($database->host)) {
        die('You not set database host in your config' . PHP_EOL);
    }

    if (!isset($database->username)) {
        die('You not set database username in your config' . PHP_EOL);
    }

    if (!isset($database->password)) {
        die('You not set database password in your config' . PHP_EOL);
    }

    if (!isset($database->dbname)) {
        die('You not set database name in your config' . PHP_EOL);
    }

    return [
        'paths' => [
            'migrations' => 'db/migrations',
            'seeds' => 'db/seeds',
        ],
        'environments' => [
            'default_migration_table' => 'migration',
            'default_database' => 'production',
            'production' => [
                'adapter' => 'pgsql',
                'host'    => $database->host,
                'name'    => $database->dbname,
                'user'    => $database->username,
                'pass'    => $database->password
            ]
        ]
    ];
}