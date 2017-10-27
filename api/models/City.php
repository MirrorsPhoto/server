<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class City extends Model
{
	protected $_tableName = 'city';

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

		$this->hasMany('id', 'Department', 'city_id', ['alias' => 'Departments']);
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
				    'message' => 'Название города обязательно для заполнения',
			    ]
		    )
	    );

        return $this->validate($validator);
    }

}
