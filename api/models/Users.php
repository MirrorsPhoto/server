<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Users extends Model
{

	protected $_tableName = 'users';

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
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
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

}
