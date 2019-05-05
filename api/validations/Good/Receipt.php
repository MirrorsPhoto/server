<?php

namespace Validator\Good;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Validation;

class Receipt extends Validation
{
	public function initialize(): void
	{
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

		$this->add(
			'price',
			new PresenceOf(
				[
					'message' => 'Код товара обязателен',
					'cancelOnFail' => true
				]
			)
		);

		$this->add(
			'price',
			new Numericality(
				[
					'message' => 'Код товара должен быть числовым значением',
				]
			)
		);
	}
}
