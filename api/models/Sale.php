<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use \Phalcon\Validation\Validator\PresenceOf;

class Sale extends Model
{

	protected $_tableName = 'sale';

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
     */
    public $good_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=32, nullable=false)
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
        $this->belongsTo('good_id', '\Good', 'id', ['alias' => 'Good']);
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

	public function beforeSave()
	{
		$this->user_id = \Core\UserCenter\Security::getUser()->id;
	}
}
