<?php

namespace Validator\Good;

use \Phalcon\Validation\Validator\PresenceOf;
use \Phalcon\Validation\Validator\Numericality;

class Add extends \Validation
{

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