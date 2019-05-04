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

	/**
	 * @param int[] $from
	 * @param mixed[] $data
	 */
	public function send(array $from, array $data): void
	{
		fwrite($this->stream, json_encode([
			'from' => $from,
			'data' => $data
		]));

		fclose($this->stream);
	}
}
