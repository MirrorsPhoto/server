<?php

class Debug
{

	public static function dumpMethodDie($var)
	{
		$objDump = new \Phalcon\Debug\Dump();

		echo $objDump->variable($var);
		die();
	}

}