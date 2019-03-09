<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Service extends Model
{

	protected $_tableName = 'service';

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
    public $datetime_create;

    public function initialize()
    {
	    parent::initialize();

	    $this->hasMany('id', 'ServicePriceHistory', 'service_id', ['alias' => 'ServicePriceHistory']);
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
					'message' => 'Название обязательно'
				]
			)
		);

		return $this->validate($validator);
	}

	public function getPrice()
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$row = $this->getServicePriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) throw new \Core\Exception\ServerError("Для услуги {$this->name} не задана цена");

		return (float) $row->price;
	}

	public static function batch($data)
	{
		$row = self::findFirst($data->id);

		for ($i = 1; $i <= $data->copies; $i++) {
			$row->sale();
		}
	}

	public function sale()
	{
		$newSaleRow = new ServiceSale([
			'service_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}

	public static function getCash(Datetime $datetime)
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$query = "select SUM(service_price_history.price) as summ from service_sale
					JOIN service_price_history ON service_price_history.service_id = service_sale.service_id AND service_sale.datetime >= service_price_history.datetime_from AND (service_sale.datetime < service_price_history.datetime_to OR service_price_history.datetime_to IS NULL) AND service_sale.department_id = service_price_history.department_id
					WHERE service_sale.datetime::date = '{$datetime->format('Y-m-d')}' AND service_sale.datetime <= '{$datetime->format('Y-m-d H:i:s')}' AND service_sale.department_id = $department_id";

		$selfObj = new self();

		$result = new Resultset(null, $selfObj, $selfObj->getReadConnection()->query($query));

		return (float) $result->getFirst()->summ;
	}

}
