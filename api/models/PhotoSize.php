<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class PhotoSize extends Model
{

	protected $_tableName = 'photo_size';

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $width;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $height;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $datetime_create;

    public function initialize()
    {
	    parent::initialize();

	    $this->hasMany('id', 'PhotoPriceHistory', 'photo_size_id', ['alias' => 'PhotoPriceHistory']);
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
			'width',
			new PresenceOf(
				[
					'message' => 'Ширина обязательна'
				]
			)
		);

		$validator->add(
			'height',
			new PresenceOf(
				[
					'message' => 'Высота обязательна'
				]
			)
		);

		$validator->add(
			'width',
			new Numericality(
				[
					'message' => 'Ширина должна быть числовым значением',
				]
			)
		);

		$validator->add(
			'height',
			new Numericality(
				[
					'message' => 'Высота должна быть числовым значением',
				]
			)
		);

		return $this->validate($validator);
	}

	public function getPrice($count)
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$row = $this->getPhotoPriceHistory("datetime_to IS NULL AND count = $count AND department_id = $department_id")->getLast();

		if (!$row) throw new \Core\Exception\ServerError("Для фотографии {$this->width}x{$this->height} с количеством {$count}шт не задана цена");

		return (float) $row->price;
	}

	/**
	 * Возвращает массив с вариацией количества фотографий для данного размера и цены
	 * @return array
	 */
	public function getVariations()
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$arrCounts = [];

		$rows = $this->getPhotoPriceHistory("datetime_to IS NULL AND department_id = $department_id");

		foreach ($rows as $row) {
			$arrCounts[$row->count] = $row->price;
		}

		return $arrCounts;
	}

	public static function batch($data)
	{
		$row = self::findFirst($data->id);

		$row->count = $data->count;

		for ($i = 1; $i <= $data->copies; $i++) {
			$row->sale();
		}
	}

	public function sale()
	{
		$newSaleRow = new PhotoSale([
			'photo_size_id' => $this->id,
			'count' => $this->count
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}

	public static function getTodayCash()
	{
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$query = "select SUM(photo_price_history.price) as summ from photo_sale
					JOIN photo_price_history ON photo_price_history.photo_size_id = photo_sale.photo_size_id AND photo_price_history.count = photo_sale.count AND photo_price_history.datetime_to IS NULL AND photo_sale.department_id = photo_price_history.department_id
					WHERE photo_sale.datetime::date = now()::date AND photo_sale.department_id = $department_id";

		$selfObj = new self();

		$result = new Resultset(null, $selfObj, $selfObj->getReadConnection()->query($query));

		return (int) $result->getFirst()->summ;
	}

}
