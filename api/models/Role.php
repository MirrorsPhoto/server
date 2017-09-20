<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Role extends Model
{
	const GUEST = 0;
	const ADMIN = 1;
	const STAFF = 2;
	const USER  = 3;

	protected $_tableName = 'role';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $name;

	/**
	 * Initialize method for model.
	 */
	public function initialize()
	{
		parent::initialize();

		$this->hasMany('id', 'User', 'role_id');
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
		    'name',
		    new PresenceOf(
			    [
				    'message' => 'Название роли обязательно для заполнения',
			    ]
		    )
	    );

        return $this->validate($validator);
    }

}
