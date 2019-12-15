<?php
//phpcs:disable

class JWT extends \Firebase\JWT\JWT
{
	use Core\Singleton;

	/**
	 * @var string
	 */
	private static $key;

	public function __construct()
	{
		self::$key = $_ENV['JWT_KEY'];
	}

	/**
	 * @param mixed[] $payload
	 *
	 * @param null $key
	 * @param string $alg
	 * @param null $keyId
	 * @param null $head
	 * @return string
	 */
	public static function encode($payload, $key = null, $alg = 'HS256', $keyId = null, $head = null): string
	{
		return parent::encode($payload, self::$key);
	}

	public static function decode($jwt, $key = null, array $allowed_algs = []): object
	{
		return parent::decode($jwt, $key ?? self::$key, $allowed_algs ?: ['HS256']);
	}
}
