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
     * @Column(type="string", nullable=false)
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

		$validator->add(
			'bar_code',
			new Uniqueness(
				[
					'message' => 'Код товара должно быть уникальным',
				]
			)
		);



		$validator->add(
			'bar_code',
			new Numericality(
				[
					'message' => 'Код товара должно быть числовым значением',
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
	 * Возвращает товары в наличии
	 *
	 * @return Resultset
	 */
	public static function getAvaible()
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$query = "select good.*, r.count - COALESCE(s.count, 0) as total from good
				join (select good_id, count(*) from good_receipt WHERE department_id = $department_id group by good_id) r on good.id = r.good_id
				left join (select good_id, count(*) from good_sale WHERE department_id = $department_id group by good_id) s on good.id = s.good_id
				WHERE r.count > COALESCE(s.count, 0);
		";

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
		$available = self::getAvaible();

		return !!$available->filter(function ($good) {
			if ($good->id == $this->id) return $good;
		});

	}

	public function sale()
	{
		$rowSale = new Sale([
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
