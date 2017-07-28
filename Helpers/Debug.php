<?php

class Debug
{

	public static function dumpDie($var)
	{
		$objDump = new \Phalcon\Debug\Dump();

		echo "<pre>";
		echo $objDump->variable($var);
		die();
	}

}