<?php

namespace Core;

trait Singleton
{

	/**
	 * @var self
	 */
	protected static $objInstance;

	public static function getInstance(): self
	{
		if (is_null(self::$objInstance)) {
			self::$objInstance = new static();
		}

		return self::$objInstance;
	}

	public static function refreshInstance(): self
	{
		self::$objInstance = new self();

		return self::$objInstance;
	}

	public function __wakeup(): void
	{
		trigger_error('Unserializing ' . self::class . ' is not allowed.', E_USER_ERROR);
	}

}
