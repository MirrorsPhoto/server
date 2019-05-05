<?php

use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class PhotoPriceHistory extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'photo_price_history';

	/**
	 * @var int
	 *
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $photo_id;

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
	 * @var integer
	 *
	 * @Column(type="integer", length=32, nullable=false)
	 */
	public $price;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_from;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=true)
	 */
	public $datetime_to;

	public function initialize(): void
	{
		parent::initialize();

		$this->belongsTo('photo_id', '\Photo', 'id', ['alias' => 'Photo']);
		$this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
	}

	public function validation(): bool
	{
		$validator = new Validation();

		$validator->add(
			'photo_id',
			new Numericality(
				[
					'message' => 'Id фото должно быть числом',
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

		$validator->add(
			'photo_size_id',
			new PresenceOf(
				[
					'message' => 'Id размера обезательное поле',
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
	 */
	public function beforeSave(): void
	{
		$user = Security::getUser();

		$this->user_id = $user->id;
		$this->department_id = $user->department_id;
	}

}
