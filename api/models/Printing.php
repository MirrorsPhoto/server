<?php

use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class Printing
 *
 * @property float price
 * @method PrintingPriceHistory getPrintingPriceHistory(string $string)
 */
class Printing extends Model
{

	protected $tableName = 'printing';

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

	/**
	 * @return float
	 * @throws ServerError
	 * @throws Unauthorized
	 */
	public function getPrice()
	{
		$department_id = Security::getUser()->department_id;

		$row = $this->getPrintingPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		if (!$row) {
			throw new ServerError("Для распечатки {$this->name} не задана цена");
		}

		return (float) $row->price;
	}

	/**
	 * @return PrintingSale
	 * @throws ServerError
	 */
	public function sale()
	{
		$newSaleRow = new PrintingSale([
			'printing_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}
}
