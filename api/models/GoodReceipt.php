<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;

class GoodReceipt extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'good_receipt';

	/**
	 * @var int
	 *
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $good_id;

	/**
	 * @var int
	 *
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $department_id;

	/**
	 * @var integer
	 *
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $user_id;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $price;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime;

	public function initialize(): void
	{
		parent::initialize();

		$this->belongsTo('good_id', '\Good', 'id', ['alias' => 'Good']);
		$this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
	}

	public function validation(): bool
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
	 */
	public function beforeSave(): void
	{
		$user = Security::getUser();

		$this->user_id = $user->id;
		$this->department_id = $user->department_id;
	}

}
