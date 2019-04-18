<?php

namespace Validator\Good;

use Phalcon\Validation\Validator\PresenceOf;
use Validation;

class Search extends Validation
{

	/**
	 * @return void
	 */
	public function initialize()
	{
		$this->add(
			'query',
			new PresenceOf(
				[
					'message' => 'Название товара обязательно'
				]
			)
		);
	}

}