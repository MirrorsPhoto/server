<?php

namespace Core;

trait Singleton
{

	/**
	 * @var self
	 */
	protected static $objInstance;

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if (is_null(self::$objInstance)) {
			self::$objInstance = new static();
		}

		return self::$objInstance;
	}

	/**
	 * @return self
	 */
	public static function refreshInstance()
	{
		self::$objInstance = new self();

		return self::$objInstance;
	}

	public function __wakeup()
	{
		trigger_error('Unserializing ' . __CLASS__ . ' is not allowed.', E_USER_ERROR);
	}
}
