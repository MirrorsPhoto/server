<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class Good extends Model
{

	protected $_tableName = 'good';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $bar_code;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->hasMany('id', 'GoodPriceHistory', 'good_id', ['alias' => 'GoodPriceHistory']);
        $this->hasMany('id', 'GoodReceipt', 'good_id', ['alias' => 'Receipts']);
        $this->hasMany('id', 'GoodSale', 'good_id', ['alias' => 'Sales']);
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
			new Uniqueness(
				[
					'message' => 'Имя товара должно быть уникальным',
				]
			)
		);

        return $this->validate($validator);
	}

	public function getPrice()
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$row = $this->getGoodPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) throw new \Core\Exception\ServerError("Для товара {$this->name} не задана цена");

		return (float) $row->price;
	}

	/**
	 * Возвращает товары в наличии или один определённый товар
	 *
	 * @param $id integer id товара
	 * @return Resultset
	 */
	public static function getAvaible($id = null)
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$query = "select good.*, r.count - COALESCE(s.count, 0) as total from good
				join (select good_id, count(*) from good_receipt WHERE department_id = $department_id group by good_id) r on good.id = r.good_id
				left join (select good_id, count(*) from good_sale WHERE department_id = $department_id group by good_id) s on good.id = s.good_id
				WHERE r.count > COALESCE(s.count, 0)";

		if ($id) {
			$query .= " AND id = $id";
		}

		$selfObj = new self();

		return new Resultset(
			null,
			$selfObj,
			$selfObj->getReadConnection()->query($query)
		);

	}

	/**
	 * Функция проверки наличия товара
	 * @return bool
	 */
	public function isAvailable()
	{
		$available = self::getAvaible($this->id);

		return !!$available->getFirst();

	}

	/**
	 * Сколько единиц данного товара в наличии
	 *
	 * @return integer
	 */
	public function getAvaibleCount()
	{
		return (self::getAvaible($this->id))->getFirst()->total | 0;
	}

	public static function batch($data)
	{
		$row = self::findFirst($data->id);

		for ($i = 1; $i <= $data->copies; $i++) {
			if (!$row->isAvailable()) throw new \Core\Exception\BadRequest("Нельзя записать продажу товара {$row->name}, т.к. его не в наличии");

			$row->sale();
		}
	}

	public function sale()
	{
		$rowSale = new GoodSale([
			'good_id' => $this->id
		]);

		return $rowSale->save();
	}

	public function receipt($price)
	{
		$rowReceipt = new GoodReceipt([
			'good_id' => $this->id,
			'price' => $price
		]);

		return $rowReceipt->save();
	}

}
