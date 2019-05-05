<?php

class JWT extends \Firebase\JWT\JWT
{

	use Core\Singleton;

	private static $key;

	public function __construct()
	{
		self::$key = $_ENV['JWT_KEY'];
	}

	/**
	 * @param array|object $payload
	 * @param string $key
	 * @param string $alg
	 * @param null $keyId
	 * @param null $head
	 * @return string
	 */
	public static function encode($payload, $key = null, $alg = 'HS256', $keyId = null, $head = null)
	{
		return parent::encode($payload, self::$key);
	}

	/**
	 * @param string $jwt
	 * @param string $key
	 * @param array $allowed_algs
	 * @return object
	 */
	public static function decode($jwt, $key = null, array $allowed_algs = array())
	{
		return parent::decode($jwt, self::$key, ['HS256']);
	}
}
