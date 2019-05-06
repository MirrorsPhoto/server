<?php

namespace Validator\Good;

use Phalcon\Validation\Validator\PresenceOf;
use Validation;

class Search extends Validation
{

	public function initialize(): void
	{
		$this->add(
			'query',
			new PresenceOf(
				[
					'message' => 'Название товара обязательно',
				]
			)
		);
	}

}
