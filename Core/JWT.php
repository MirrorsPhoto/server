<?php

class JWT extends \Firebase\JWT\JWT
{

	use \Core\Singleton;

	private static $key;

	public function __construct()
	{
		$config = ConfigIni::getInstance();
		self::$key = $config->jwt->key;
	}

	public static function encode($payload, $key = null, $alg = 'HS256', $keyId = null, $head = null)
	{
		return parent::encode($payload, self::$key);
	}

	public static function decode($jwt, $key = null, array $allowed_algs = array())
	{
		return parent::decode($jwt, self::$key, ['HS256']);
	}

}