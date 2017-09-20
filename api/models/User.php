<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class User extends Model
{

	protected $_tableName = 'user';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $username;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $first_name;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $last_name;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $middle_name;

	/**
	 *
	 * @var integer
	 * @Column(type="integer", nullable=false)
	 */
    public $role;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $email;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime_create;

	/**
	 *
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $token;

	/**
	 *
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $avatar_id;

	/**
	 * Initialize method for model.
	 */
	public function initialize()
	{
		parent::initialize();

		$this->belongsTo('avatar_id', '\File', 'id', ['alias' => 'Avatar']);
		$this->belongsTo('role_id', '\Role', 'id');
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
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Введите корректный адрес',
                ]
            )
        );

        return $this->validate($validator);
    }

}
