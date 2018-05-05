<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Department
 *
 * @method getUsers($con)
 */
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
		$this->hasManyToMany(
			'id',
			'DepartmentPersonnelHistory',
			'department_id', 'user_id',
			'User',
			'id',
			[
				'alias' => 'Users'
			]
		);
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


	/**
	 * Метод для получения всех текущих сотрудников данного салона
	 *
	 * @return Phalcon\Mvc\Model\Resultset\Simple
	 */
	public function getCurrentPersonnel()
    {
    	return $this->getUsers('datetime_to IS NULL');
    }

	public function notifyPersonnels()
	{
		$userRows = $this->getCurrentPersonnel();

		$arrUserIds = array_column($userRows->toArray(), 'id');

		$data = [
			'cash' => [
				'photo' => Photo::getTodayCash(),
				'good' => Good::getTodayCash(),
				'copy' => Copy::getTodayCash(),
				'lamination' => Lamination::getTodayCash(),
				'printing' => Printing::getTodayCash()
			],
			'client_count' => Check::getTodayClientCount()
		];

		$socket = WebSocket::getInstance();

		$socket->send($arrUserIds, $data);
	}

}
