<?php

abstract class Validation extends \Phalcon\Validation
{

	public function validate($data = null, $entity = null)
	{
		parent::validate($data ?? $_POST, $entity);
	}

	public function afterValidation($data, $entity, $messages)
	{
		$totalCountMessages = $messages->count();

		if (!$totalCountMessages) return;

		$errorMessage = [];

		foreach ($messages as $message) {
			$errorMessage[] = $message->getMessage();
		}

		throw new \Core\Exception\BadRequest($errorMessage);
	}

}