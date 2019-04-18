<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class DepartmentPersonnelHistory extends Model
{

	/**
	 * @var string
	 */
	protected $_tableName = 'department_personnel_history';

	/**
	 * @var int
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $department_id;

	/**
	 * @var int
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $user_id;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_from;

	/**
	 *
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	public $datetime_to;

	/**
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		$this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
		$this->belongsTo('department_id', '\Department', 'id', ['alias' => 'Department']);
	}

	/**
	 * @return boolean
	 */
	public function validation() {
		$validator = new Validation();

		$validator->add(
			'department_id',
			new Numericality(
				[
					'message' => 'Id салона должно быть числом',
				]
			)
		);

		$validator->add(
			'user_id',
			new Numericality(
				[
					'message' => 'Id пользователя должно быть числом',
				]
			)
		);

		$validator->add(
			'department_id',
			new PresenceOf(
				[
					'message' => 'Id салона обезательное поле',
				]
			)
		);

		$validator->add(
			'user_id',
			new PresenceOf(
				[
					'message' => 'Id пользователя обезательное поле',
				]
			)
		);

		return $this->validate($validator);
	}

}
