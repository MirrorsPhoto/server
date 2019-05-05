<?php

use Phalcon\Debug\Dump;

class Debug
{

	/**
	 * @param mixed $obj
	 */
	public static function dumpMethodDie($obj): void
	{
		$objDump = new Dump();

		echo "<pre>";
		echo $objDump->variable($obj);

		debug_print_backtrace();

		die();
	}

	/**
	 * @param mixed $var
	 */
	public static function dumpDie($var): void
	{
		echo "<pre>";
		var_dump($var);
		debug_print_backtrace();
		die();
	}

}
