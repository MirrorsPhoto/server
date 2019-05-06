<?php

namespace Validator\Good;

use Phalcon\Validation\Validator\PresenceOf;
use Validation;

class Add extends Validation
{

	public function initialize(): void
	{
		$this->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Имя товара обязателено',
				]
			)
		);
	}

}
