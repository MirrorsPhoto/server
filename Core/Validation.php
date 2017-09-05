<?php

abstract class Validation extends \Phalcon\Validation
{

	public function validate($data = null, $entity = null)
	{
		$post = $this->request->getPost();
		$get = $this->request->getQuery();
		$put = $this->request->getPut();
		
		parent::validate($data ?? array_merge($post, $get, $put) , $entity);
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