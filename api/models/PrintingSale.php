<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class PrintingSale extends Model
{

	/**
	 * @var string
	 */
	protected $_tableName = 'printing_sale';

	/**
	 * @var int
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $printing_id;

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
	public $datetime;

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->belongsTo('printing_id', '\Printing', 'id', ['alias' => 'Printing']);
		$this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
	}

	/**
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'printing_id',
			new Numericality(
				[
					'message' => 'Id распечатки должно быть числом',
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
			'printing_id',
			new PresenceOf(
				[
					'message' => 'Id распечатки обязательное поле',
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
	 * @return void
	 */
	public function beforeSave()
	{
		$user = Security::getUser();

		$this->user_id = $user->id;
		$this->department_id = $user->department_id;
	}

}
