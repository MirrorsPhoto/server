<?php

use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Service
 *
 * @property float price
 * @method ServicePriceHistory getServicePriceHistory(string $string)
 */
class Service extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'service';

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $name;

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $datetime_create;

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->hasMany('id', 'ServicePriceHistory', 'service_id', ['alias' => 'ServicePriceHistory']);
	}

	/**
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Название обязательно'
				]
			)
		);

		return $this->validate($validator);
	}

	/**
	 * @throws Unauthorized
	 * @return float
	 */
	public function getPrice()
	{
		$department_id = Security::getUser()->department_id;

		$row = $this->getServicePriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		return (float) $row->price ?? 0;
	}

	/**
	 * @throws ServerError
	 * @return ServiceSale
	 */
	public function sale()
	{
		$newSaleRow = new ServiceSale([
			'service_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}
}
