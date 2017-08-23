<?php

namespace Validator\Good;

use \Phalcon\Validation\Validator\PresenceOf;
use \Phalcon\Validation\Validator\Numericality;

class Login extends \Validation
{

	public function initialize()
	{
		$this->add(
			'bar_code',
			new PresenceOf(
				[
					'message' => 'Код товара обязателен',
				]
			)
		);

		$this->add(
			'bar_code',
			new Numericality(
				[
					'message' => 'Код товара должен быть числовым значением',
				]
			)
		);
	}

}