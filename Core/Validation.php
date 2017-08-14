<?php

abstract class Validation extends \Phalcon\Validation
{

	public function validate($data = null, $entity = null)
	{
		parent::validate($data ?? $_POST, $entity);
	}

	public function afterValidation($data, $entity, $messages)
	{
		if (!$messages->count()) return;

		$errorMessage = '';

		foreach ($messages as $message) {
			$errorMessage .= $message->getMessage() . '. ';
		}

		throw new \Core\Exception\BadRequest($errorMessage);
	}

}