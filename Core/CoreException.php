<?php
abstract class CoreException extends Exception
{
	public function __construct(string $message = "", int $code = 0)
	{
		parent::__construct();

		$this->message = $message;
		$this->code = $code;
	}
}
