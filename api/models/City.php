<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class City extends Model
{

	/**
	 * @var string
	 */
	protected $_tableName = 'city';

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	/**
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		$this->hasMany('id', 'Department', 'city_id', ['alias' => 'Departments']);
	}

	/**
	 * @return boolean
	 */
	public function validation() {
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
