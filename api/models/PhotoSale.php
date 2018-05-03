<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;

class PhotoSale extends Model
{

	protected $_tableName = 'photo_sale';

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $photo_id;

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
        $this->belongsTo('photo_id', '\Photo', 'id', ['alias' => 'Photo']);
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
			'photo_id',
			new Numericality(
				[
					'message' => 'Id фотографии должно быть числом',
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
			'photo_id',
			new PresenceOf(
				[
					'message' => 'Id фотографии обязательное поле',
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
