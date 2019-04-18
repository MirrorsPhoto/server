<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class CopyPriceHistory extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'copy_price_history';

	/**
	 * @var int
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $copy_id;

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
	 * @var int
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $price;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_from;

	/**
	 * @var string
	 * @Column(type="string", nullable=true)
	 */
	public $datetime_to;

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
		$this->belongsTo('copy_id', '\Copy', 'id', ['alias' => 'Copy']);
	}

	/**
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'user_id',
			new Numericality(
				[
					'message' => 'Id пользователя должно быть числом',
				]
			)
		);

		$validator->add(
			'copy_id',
			new Numericality(
				[
					'message' => 'Id копии должно быть числом',
					]
			)
		);

		$validator->add(
			'price',
			new Numericality(
				[
					'message' => 'Цена должна быть числом',
				]
			)
		);

		$validator->add(
			'copy_id',
			new PresenceOf(
				[
					'message' => 'Id копии обезательное поле',
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

		$validator->add(
			'price',
			new PresenceOf(
				[
					'message' => 'Цена обезательное поле',
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
