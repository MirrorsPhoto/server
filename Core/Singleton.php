<?php

namespace Core;

trait Singleton
{

	/**
	 * @var self
	 */
	protected static $_objInstance;

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if (is_null(self::$_objInstance))
		{
			self::$_objInstance = new static();
		}

		return self::$_objInstance;
	}

	/**
	 * @return self
	 */
	public static function refreshInstance()
	{
		self::$_objInstance = new self();

		return self::$_objInstance;
	}

	public function __wakeup()
	{
		trigger_error('Unserializing ' . __CLASS__ . ' is not allowed.', E_USER_ERROR);
	}

}