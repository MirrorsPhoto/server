<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Role extends Model
{
	const GUEST = 1;
	const ADMIN = 2;
	const STAFF = 3;
	const USER  = 4;

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

		$this->hasMany('id', 'User', 'role_id', [ 'alias' => 'Users' ]);
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

    public function getPhrase()
    {
      switch ($this->id) {
        case 2:
          $phrase = 'admin';
          break;
        case 3:
          $phrase = 'staff';
          break;

        default:
          throw new \Core\Exception\ServerError('Not found phrase role for ' . $this->name);
      }

      return "user.roles.$phrase";
    }

}
