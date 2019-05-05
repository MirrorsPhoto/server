<?php

namespace Validator\Good;

use Phalcon\Validation\Validator\PresenceOf;
use Validation;

class Add extends Validation
{

	/**
	 * @return void
	 */
	public function initialize()
	{
		$this->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Имя товара обязателено'
				]
			)
		);
	}
}
