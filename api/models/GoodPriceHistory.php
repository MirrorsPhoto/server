<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;

class GoodPriceHistory extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'good_price_history';

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
	 *
	 * @var int
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $user_id;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
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
			new Numericality(
				[
					'message' => 'Id товара должно быть числом',
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
			'price',
			new Numericality(
				[
					'message' => 'Цена должна быть числом',
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
