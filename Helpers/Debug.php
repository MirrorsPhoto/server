<?php

class Debug
{

	public static function dumpMethodDie($obj)
	{
		$objDump = new \Phalcon\Debug\Dump();

		echo $objDump->variable($obj);
		die();
	}

	public static function dumpDie($var)
	{
		echo "<pre>";
		var_dump($var);
		die();
	}

}