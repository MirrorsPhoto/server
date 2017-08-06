<?php

class JWT extends \Firebase\JWT\JWT
{

	private static $key = 'devkey';

	public static function encode($payload)
	{
		return parent::encode($payload, self::$key);
	}

	public static function decode($jwt)
	{
		return parent::decode($jwt, self::$key, ['HS256']);
	}

}