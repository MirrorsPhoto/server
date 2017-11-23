<?php

namespace Validator\Good;

use \Phalcon\Validation\Validator\PresenceOf;
use \Phalcon\Validation\Validator\Numericality;

class Search extends \Validation
{

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