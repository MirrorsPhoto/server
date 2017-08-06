<?php

class JWT extends \Firebase\JWT\JWT
{

	private static $key = 'devkey';

	public static function encode($payload, $key = null, $alg = 'HS256', $keyId = null, $head = null)
	{
		return parent::encode($payload, self::$key);
	}

	public static function decode($jwt, $key = null, array $allowed_algs = array())
	{
		return parent::decode($jwt, self::$key, ['HS256']);
	}

}