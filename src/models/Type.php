<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Type extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'type';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Название роли обязательно для заполнения',
				]
			)
		);

		return $this->validate($validator);
	}

}
