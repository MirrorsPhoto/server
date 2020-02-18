<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class UserType extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'user_type';

	/**
	 * @var int
	 *
	 * @Column(type="integer", nullable=false)
	 */
	public $user_id;

	/**
	 * @var int
	 *
	 * @Column(type="integer", nullable=false)
	 */
	public $type_id;

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
