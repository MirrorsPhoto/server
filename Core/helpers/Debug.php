<?php

class Debug
{

	public static function dumpMethodDie($obj)
	{
		$objDump = new \Phalcon\Debug\Dump();

		echo "<pre>";
		echo $objDump->variable($obj);
		debug_print_backtrace();
		die();
	}

	public static function dumpDie($var)
	{
		echo "<pre>";
		var_dump($var);
		debug_print_backtrace();
		die();
	}

}