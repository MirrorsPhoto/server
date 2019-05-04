<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class LaminationSale extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'lamination_sale';

	/**
	 * @var int
	 *
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $lamination_id;

	/**
	 * @var int
	 *
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $department_id;

	/**
	 * @var int
	 *
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $user_id;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime;

	public function initialize(): void
	{
		parent::initialize();

		$this->belongsTo('lamination_id', '\Lamination', 'id', ['alias' => 'Lamination']);
		$this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'lamination_id',
			new Numericality(
				[
					'message' => 'Id размера ламинации должно быть числом',
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
			'lamination_id',
			new PresenceOf(
				[
					'message' => 'Id размера ламинации обязательное поле',
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

	/**
	 * @throws Unauthorized
	 */
	public function beforeSave(): void
	{
		$user = Security::getUser();

		$this->user_id = $user->id;
		$this->department_id = $user->department_id;
	}
}
