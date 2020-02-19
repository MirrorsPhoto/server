<?php

use Core\Exception\ServerError;

class WebSocket
{

	use Core\Singleton;

	/**
	 * @var resource
	 */
	private $stream;

	/**
	 * WebSocket constructor.
	 *
	 * @param string $address
	 * @throws ServerError
	 */
	public function __construct(string $address = 'tcp://websocket:1337')
	{
		$this->stream = stream_socket_client($address);

		if (!$this->stream) {
			throw new ServerError('Не удаётся установить соединение с WebSocket сервером');
		}
	}

	public function __destruct()
	{
		fclose($this->stream);
	}

	/**
	 * @param int $to
	 * @param mixed[] $data
	 */
	public function send(int $to, array $data): void
	{
		fwrite($this->stream, json_encode([
			'to' => $to,
			'data' => $data,
		]));
	}

}
