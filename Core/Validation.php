<?php

use Core\Exception\BadRequest;
use Phalcon\Validation\Message\Group;

abstract class Validation extends Phalcon\Validation
{
	/**
	 * @param mixed $data
	 * @param mixed $entity
	 */
	public function validate($data = null, $entity = null): void
	{
		$post = $this->request->getPost();
		$get = $this->request->getQuery();
		$put = $this->request->getPut();

		parent::validate($data ?? array_merge($post, $get, $put), $entity);
	}

	/**
	 * @param mixed $data
	 * @param mixed $entity
	 * @param Group $messages
	 * @throws BadRequest
	 */
	// @codingStandardsIgnoreLine SlevomatCodingStandard.Variables.UnusedVariable
	public function afterValidation($data, $entity, Group $messages): void
	{
		$totalCountMessages = $messages->count();

		if (!$totalCountMessages) {
			return;
		}

		$errorMessage = [];

		foreach ($messages as $message) {
			$errorMessage[] = $message->getMessage();
		}

		throw new BadRequest($errorMessage);
	}
}
