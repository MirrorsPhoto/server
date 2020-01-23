<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class UserNotificationDevice
 *
 * @method User getUser()
 * @method static self findFirstByDeviceToken($token)
 */
class UserNotificationDevice extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'user_notification_device';

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $user_id;

	/**
	 * @var string
	 *
	 * @Column(type="string", nullable=false)
	 */
	public $device_token;

	public function initialize(): void
	{
		parent::initialize();

		$this->hasOne('user_id', '\User', 'id', ['alias' => 'User']);
	}

	public function validation(): bool
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
			'user_id',
			new PresenceOf(
				[
					'message' => 'Id пользователя обязательное поле',
				]
			)
		);

		$validator->add(
			'device_token',
			new PresenceOf(
				[
					'message' => 'Токен устройства обязательное поле',
				]
			)
		);

		return $this->validate($validator);
	}

}
