<?php

use Core\Exception\BadRequest;

abstract class Validation extends Phalcon\Validation
{

	/**
	 * @param null $data
	 * @param null $entity
	 */
	public function validate($data = null, $entity = null)
	{
		$post = $this->request->getPost();
		$get = $this->request->getQuery();
		$put = $this->request->getPut();

		parent::validate($data ?? array_merge($post, $get, $put) , $entity);
	}

	/**
	 * @param $data
	 * @param $entity
	 * @param $messages
	 * @throws BadRequest
	 */
	public function afterValidation($data, $entity, $messages)
	{
		$totalCountMessages = $messages->count();

		if (!$totalCountMessages) return;

		$errorMessage = [];

		foreach ($messages as $message) {
			$errorMessage[] = $message->getMessage();
		}

		throw new BadRequest($errorMessage);
	}

}