<?php
abstract class CoreException extends Exception
{

	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		$this->message = $message;
		$this->code = $code;
	}

}