<?php

namespace Validator\Photo;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Validation;

class Price extends Validation
{

	/**
	 * @return void
	 */
	public function initialize() {
		$this->add(
			'width',
			new PresenceOf(
				[
					'message' => 'Ширина фотографии обязателена'
				]
			)
		);

		$this->add(
			'height',
			new PresenceOf(
				[
					'message' => 'Высота фотографии обязателена'
				]
			)
		);

		$this->add(
			'count',
			new PresenceOf(
				[
					'message' => 'Количество фотографий обязательно'
				]
			)
		);

		$this->add(
			'width',
			new Numericality(
				[
					'message' => 'Ширина фотографии должена быть числовым значением'
				]
			)
		);

		$this->add(
			'height',
			new Numericality(
				[
					'message' => 'Высота фотографии должена быть числовым значением'
				]
			)
		);

		$this->add(
			'count',
			new Numericality(
				[
					'message' => 'Количество фотографий должено быть числовым значением'
				]
			)
		);

	}

}