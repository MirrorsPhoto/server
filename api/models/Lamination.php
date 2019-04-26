<?php

use Core\Exception\ServerError;
use Core\UserCenter\Exception\Unauthorized;
use Core\UserCenter\Security;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * @property float price
 * @method LaminationPriceHistory getLaminationPriceHistory(string $string)
 */
class Lamination extends Model
{

	/**
	 * @var string
	 */
	protected $tableName = 'lamination';

	/**
	 * @var string
	 * @Column(type="string", nullable=false)
	 */
	public $format;

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

		$this->hasMany('id', 'LaminationPriceHistory', 'lamination_id', ['alias' => 'LaminationPriceHistory']);
	}

	/**
	 * @return boolean
	 */
	public function validation()
	{
		$validator = new Validation();

		$validator->add(
			'format',
			new PresenceOf(
				[
					'message' => 'Формат обязательн'
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

		$row = $this->getLaminationPriceHistory("datetime_to IS NULL AND department_id = $department_id")->getLast();

		return (float) $row->price ?? 0;
	}

	/**
	 * @param mixed $data
	 * @throws ServerError
	 */
	public static function batch($data)
	{
		$row = self::findFirst($data->id);

		for ($i = 1; $i <= $data->copies; $i++) {
			$row->sale();
		}
	}

	/**
	 * @throws ServerError
	 * @return LaminationSale
	 */
	public function sale()
	{
		$newSaleRow = new LaminationSale([
			'lamination_id' => $this->id,
		]);

		$newSaleRow->save();

		return $newSaleRow;
	}
}
