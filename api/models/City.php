<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class City extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'city';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasMany('id', 'Department', 'city_id', ['alias' => 'Departments']);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Название города обязательно для заполнения',
				]
			)
		);

		return $this->validate($validator);
	}
}
