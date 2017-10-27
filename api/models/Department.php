<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Department extends Model
{
	protected $_tableName = 'department';

	/**
	 *
	 * @var integer
	 * @Column(type="integer", nullable=false)
	 */
	public $city_id;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $name;

	/**
	 *
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $address;

	/**
	 * Initialize method for model.
	 */
	public function initialize()
	{
		parent::initialize();

		$this->belongsTo('city_id', '\City', 'id', ['alias' => 'City']);
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
		    'city_id',
		    new PresenceOf(
			    [
				    'message' => 'Город обязателен',
			    ]
		    )
	    );

	    $validator->add(
		    'name',
		    new PresenceOf(
			    [
				    'message' => 'Название салона обязательно',
			    ]
		    )
	    );

	    $validator->add(
		    'address',
		    new PresenceOf(
			    [
				    'message' => 'Адрес салона обязателен',
			    ]
		    )
	    );

        return $this->validate($validator);
    }

}
