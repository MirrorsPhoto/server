<?php

use Core\Exception\ServerError;

class WebSocket
{

	use Core\Singleton;

	/**
	 * @var resource
	 */
	private $_stream;

	/**
	 * WebSocket constructor.
	 * @param string $address
	 * @throws ServerError
	 */
	public function __construct($address = 'tcp://websocket:1337')
	{
		$this->_stream = stream_socket_client($address);

		if (!$this->_stream) {
			throw new ServerError('Не удаётся установить соединение с WebSocket сервером');
		}
	}

	/**
	 * @param $from
	 * @param $data
	 */
	public function send($from, $data)
	{
		fwrite($this->_stream, json_encode([
			'from' => $from,
			'data' => $data
		]));

		fclose($this->_stream);
	}

}