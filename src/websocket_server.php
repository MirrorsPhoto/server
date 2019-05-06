<?php
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
use Firebase\JWT\JWT;

// массив для связи соединения пользователя и необходимого нам параметра
$users = [];

// создаём ws-сервер, к которому будут подключаться все наши пользователи
$ws_worker = new Worker('websocket://0.0.0.0:8000');

// создаём обработчик, который будет выполняться при запуске ws-сервера
$ws_worker->onWorkerStart = function () use (&$users): void {
	// создаём локальный tcp-сервер, чтобы отправлять на него сообщения из кода нашего сайта
	$inner_tcp_worker = new Worker('tcp://websocket:1337');

	// создаём обработчик сообщений, который будет срабатывать,
	// когда на локальный tcp-сокет приходит сообщение
	// @codingStandardsIgnoreLine SlevomatCodingStandard.Variables.UnusedVariable
	$inner_tcp_worker->onMessage = function ($connection, $data) use (&$users): void {
		$data = json_decode($data);

		//Обходим все id пользователей, которым нужно отправить сообщение
		foreach ($data->from as $fromId) {
			//Если такой пользователь не подключен - далее
			if (!isset($users[$fromId])) {
				continue;
			}

			//Обходим все соединения пользователя и отправляем и сообщение
			foreach ($users[$fromId] as $connect) {
				$connect->send(json_encode($data->data));
			}
		}
	};

	//Слушаем данные от сервера
	$inner_tcp_worker->listen();
};

$ws_worker->onConnect = function ($connection) use (&$users): void {
	$connection->onWebSocketConnect = function ($connection) use (&$users): void {
		//Если не передан JWT - закрываем соединение
		if (!isset($_GET['token'])) {
			$connection->close();
			die;
		}

		$token = $_GET['token'];

		//Валидация токена
		try {
			$newUser = JWT::decode($token, $_ENV['JWT_KEY'], ['HS256']);
		} catch (UnexpectedValueException $e) {
			echo $e->getMessage() . "\n";

			$connection->close();
			die;
		}

		// при подключении нового пользователя сохраняем его id, который достали из токена
		$users[$newUser->id][] = $connection;
	};
};

$ws_worker->onClose = function ($connection) use (&$users): void {
	//Удаляем соединение
	foreach ($users as &$user) {
		$connect = array_search($connection, $user);

		unset($user[$connect]);
	}
};

// Запуск всей байды
Worker::runAll();
