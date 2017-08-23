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

		$this->add(
			'bar_code',
			new PresenceOf(
				[
					'message' => 'Код товара обязателен',
					'cancelOnFail' => true
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