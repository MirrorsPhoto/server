<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Printing extends Model
{

	protected $_tableName = 'printing';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $name;

	/**
	 *
	 * @var string
	 * @Column(type="boolean", nullable=false)
	 */
	public $color;

	/**
	 *
	 * @var string
	 * @Column(type="boolean", nullable=false)
	 */
	public $photo;

	/**
	 *
	 * @var string
	 * @Column(type="boolean", nullable=false)
	 */
	public $ext;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime_create;

    public function initialize()
    {
	    parent::initialize();

	    $this->hasMany('id', 'PrintingPriceHistory', 'printing_id', ['alias' => 'PrintingPriceHistory']);
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

		$row = $this->getPrintingPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) throw new \Core\Exception\ServerError("Для распечатки {$this->name} не задана цена");

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
		$newSaleRow = new PrintingSale([
			'printing_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}

	public static function getCash(Datetime $datetime)
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$query = "select SUM(printing_price_history.price) as summ from printing_sale
					JOIN printing_price_history ON printing_price_history.printing_id = printing_sale.printing_id AND printing_sale.datetime >= printing_price_history.datetime_from AND (printing_sale.datetime < printing_price_history.datetime_to OR printing_price_history.datetime_to IS NULL) AND printing_sale.department_id = printing_price_history.department_id
					WHERE printing_sale.datetime::date = '{$datetime->format('Y-m-d')}' AND printing_sale.datetime <= '{$datetime->format('Y-m-d H:i:s')}' AND printing_sale.department_id = $department_id";

		$selfObj = new self();

		$result = new Resultset(null, $selfObj, $selfObj->getReadConnection()->query($query));

		return (float) $result->getFirst()->summ;
	}

}
