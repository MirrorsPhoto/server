<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class GoodSale extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'good_sale';

	/**
	 * @var int
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $good_id;

	/**
	 * @var int
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $department_id;

	/**
	 * @var int
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $user_id;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $datetime;

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->belongsTo('good_id', '\Good', 'id', ['alias' => 'Good']);
		$this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
	}

	/**
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'good_id',
			new PresenceOf(
				[
					'message' => 'Id товара обязателен',
					'cancelOnFail' => true
				]
			)
		);

		$validator->add(
			'good_id',
			new Numericality(
				[
					'message' => 'Id товара должно быть числом',
				]
			)
		);

		$validator->add(
			'user_id',
			new PresenceOf(
				[
					'message' => 'Id пользователя обязателен',
					'cancelOnFail' => true
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

		return $this->validate($validator);
	}

	/**
	 * @throws Unauthorized
	 * @return void
	 */
	public function beforeSave()
	{
		$user = Security::getUser();

		$this->user_id = $user->id;
		$this->department_id = $user->department_id;
	}
}
