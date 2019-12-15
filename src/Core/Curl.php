<?php
//phpcs:disable

namespace Core;

use Core\Exception\ServerError;

class Curl
{

	use Singleton;

	private $curl;

	public function __construct()
	{
		$this->curl = new \Curl\Curl();
	}

	/**
	 * @param string $url
	 *
	 * @throws ServerError
	 *
	 * @return mixed
	 */
	public function get(string $url)
	{
		return $this->request('get', $url);
	}

	/**
	 * @param string $method
	 * @param string $url
	 *
	 * @throws ServerError
	 *
	 * @return null
	 */
	private function request(string $method, string $url)
	{
		$this->curl->get($url);

		if ($this->curl->error) {
			throw new ServerError($this->curl->errorMessage);
		}

		return $this->curl->response;
	}

}
