<?php

class WebSocket
{

	use \Core\Singleton;

	private $_stream;

	public function __construct($address = 'tcp://websocket:1337')
	{
		if (!$this->_stream = stream_socket_client($address)) {
			throw new \Core\Exception\ServerError('Не удаётся установить соединение с WebSocket сервером');
		}
	}

	public function send($from, $data)
	{
		fwrite($this->_stream, json_encode([
			'from' => $from,
			'data' => $data
		]));
		fclose($this->_stream);
	}

}