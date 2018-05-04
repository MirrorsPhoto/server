<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class ServiceSale extends Model
{

	protected $_tableName = 'service_sale';

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $service_id;

	/**
	 *
	 * @var integer
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $department_id;

	/**
	 *
	 * @var integer
	 * @Column(type="integer", length=11, nullable=false)
	 */
	public $user_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->belongsTo('service_id', '\Service', 'id', ['alias' => 'Service']);
        $this->belongsTo('user_id', '\User', 'id', ['alias' => 'User']);
    }

	/**
	 * Validations and business logic
	 *
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'service_id',
			new Numericality(
				[
					'message' => 'Id услуги должно быть числом',
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
			'service_id',
			new PresenceOf(
				[
					'message' => 'Id услуги обязательное поле',
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

	public function beforeSave()
	{
		$user = \Core\UserCenter\Security::getUser();

		$this->user_id = $user->id;
		$this->department_id = $user->department_id;
	}

}
