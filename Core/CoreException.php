<?php
abstract class CoreException extends Exception
{

	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		parent::__construct();

		$this->message = $message;
		$this->code = $code;
	}

}