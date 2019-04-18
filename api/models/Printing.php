<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * Class Printing
 *
 * @property float price
 */
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

	public function initialize() {
		parent::initialize();

		$this->hasMany('id', 'PrintingPriceHistory', 'printing_id', ['alias' => 'PrintingPriceHistory']);
	}

	/**
	 * Validations and business logic
	 *
	 * @return boolean
	 */
	public function validation() {
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

	public function getPrice() {
		$department_id = Core\UserCenter\Security::getUser()->department_id;

		$row = $this->getPrintingPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) {
throw new \Core\Exception\ServerError("Для распечатки {$this->name} не задана цена");
		}

		return (float)$row->price;
	}

	public static function batch($data) {
		$row = self::findFirst($data->id);

		for ($i = 1; $i <= $data->copies; $i++) {
			$row->sale();
		}
	}

	public function sale() {
		$newSaleRow = new PrintingSale([
			'printing_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}

}
